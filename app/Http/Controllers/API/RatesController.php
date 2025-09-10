<?php

namespace App\Http\Controllers\API;

use App\Enums\PackageType;
use App\Enums\RateType;
use App\Http\Controllers\Controller;
use App\Models\Country;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RatesController extends Controller
{
    /**
     * Test database connection
     */
    public function testDb()
    {
        try {
            DB::connection()->getPdo();
            $count = DB::table('countries')->count();
            return response()->json([
                'status' => 'success',
                'message' => 'Database connected successfully',
                'countries_count' => $count,
                'db_config' => [
                    'connection' => config('database.default'),
                    'host' => config('database.connections.mysql.host'),
                    'database' => config('database.connections.mysql.database'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'db_config' => [
                    'connection' => config('database.default'),
                    'host' => config('database.connections.mysql.host'),
                    'database' => config('database.connections.mysql.database'),
                ]
            ], 500);
        }
    }

    /**
     * Retrieve sender country
     *
     **/
    public function sender(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'country_code' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->validationError($validation->errors()->first());
            }

            $country = Country::where('code', $request->country_code)->first();
            if(!$country){
                return response()->validationError(Country::NAME . ' not found');
            }
        } catch (\Exception $e) {
            \Log::error('Sender method error: ' . $e->getMessage());
            return response()->json(['error' => 'Database connection error: ' . $e->getMessage()], 500);
        }

        return response()->success('Successfully retrieve sender country', [
            'name' => $country->name,
            'code' => $country->code,
            'currency_code' => $country->currency_code,
            'hide_package_opt_en' => $country->hide_package_opt_en,
            'hide_package_opt_local' => $country->hide_package_opt_local,
            'doc_max_weight' => $country->doc_max_weight,
            'non_doc_max_weight' => $country->non_doc_max_weight,
            'hide_step_1' => $country->hide_step_1,
            'last_update' => $country->updated_at,
        ]);
    }

    /**
     * Retrieve package type
     *
     **/
    public function packageType(Request $request)
    {
        $lang = 'en';

        if($request->lang){
            $lang = str_replace('/', '', $request->lang);
            if($lang !== 'en'){
                $lang = strtolower($request->country_code);
            }
        }

        App::setLocale($lang);
        $packageTypes = PackageType::asSelectArray();
        return response()->success('Successfully retrieve package type', $packageTypes);
    }

    /**
     * Retrieve receiver list in specified country
     *
     **/
    public function receiver(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'country_code' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->validationError($validation->errors()->first());
        }

        $country = Country::where('code', $request->country_code)->first();
        if(!$country){
            return response()->validationError(Country::NAME . ' not found');
        }

        $receivers = $country->receivers()->get();
        $receivers->transform(function ($receiver) {
            return [
                'id' => $receiver->id,
                'name' => $receiver->name,
            ];
        });

        return response()->success('Successfully retrieve receiver list', $receivers);
    }

    /**
     * Calculate rate
     *
     * Implements strong input validation, allowlist, and secure coding practices.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        // Strong input validation with allowlist, type, length, and format restrictions
        $validation = Validator::make($request->all(), [
            'country_code' => [
                'required',
                'string',
                'alpha',
                'exists:countries,code'
            ],
            'receiver_id' => 'required|integer|min:1',
            'packages' => 'required|array|min:1|max:10',
            'packages.*.type' => ['required', 'integer', new EnumValue(PackageType::class)],
            'packages.*.weight' => 'required|numeric|min:0.01|max:1000',
        ]);

        if ($validation->fails()) {
            return response()->validationError($validation->errors()->first());
        }

        // Get country by code (already validated)
        $country = Country::where('code', $request->country_code)->first();
        if(!$country){
            return response()->validationError(Country::NAME . ' not found');
        }

        // Validate each package for business rules
        foreach($request->packages as $package){
            // Document package weight restriction
            if($package['weight'] > $country->doc_max_weight && $package['type'] == PackageType::Document){
                return response()->validationError('Documents package cannot be more than 2kg');
            }
            // Non-document package weight restriction
            if($package['weight'] > $country->non_doc_max_weight){
                return response()->validationError('Package cannot be more than 30kg');
            }
        }

        $receiver = $country->receivers()->where('id', $request->receiver_id)->first();
        if(!$receiver){
            return response()->validationError('Receiver country not found');
        }

        $overall_rates = collect();

        foreach($request->packages as $key => $package){
            $rates = $country->rates()->where('package_type', $package['type'])
                            ->where('weight', '=', $package['weight'])
                            ->where('zone', $receiver->zone)
                            ->orderBy('weight', 'asc')
                            ->get();

            if(!$rates){
                return response()->validationError('Rate for package '.$key.' not found');
            }

            $overall_rates = $overall_rates->merge($rates);
        }

        $personal = $overall_rates->where('type', RateType::Personal)->sum('price');
        $business = $overall_rates->where('type', RateType::Business)->sum('price');
        $original = $overall_rates->where('type', RateType::Original)->sum('price');

        $is_set = $country->quote_langs ? true : false;

        $business_title_en = $is_set ? $country->quote_langs()->where('name', 'business_title_en')->first() : NULL;
        $business_title_local = $is_set ? $country->quote_langs()->where('name', 'business_title_local')->first() : NULL;
        $business_content_en = $is_set ? $country->quote_langs()->where('name', 'business_content_en')->first() : NULL;
        $business_content_local = $is_set ? $country->quote_langs()->where('name', 'business_content_local')->first() : NULL;
        $business_additional_list_en = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_en')->first() : NULL;
        $business_additional_list_local = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_local')->first() : NULL;
        $business_additional_list_value_en = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_value_en')->first() : NULL;
        $business_additional_list_value_local = $is_set ? $country->quote_langs()->where('name', 'business_additional_list_value_local')->first() : NULL;
        $business_condition_list_en = $is_set ? $country->quote_langs()->where('name', 'business_condition_list_en')->first() : NULL;
        $business_condition_list_local = $is_set ? $country->quote_langs()->where('name', 'business_condition_list_local')->first() : NULL;
        $business_cta_btn_text_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_en')->first() : NULL;
        $business_cta_btn_text_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_local')->first() : NULL;
        $business_cta_btn_link_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_en')->first() : NULL;
        $business_cta_btn_link_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_local')->first() : NULL;
        $business_cta_btn_text_2_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_2_en')->first() : NULL;
        $business_cta_btn_text_2_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_text_2_local')->first() : NULL;
        $business_cta_btn_link_2_en = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_2_en')->first() : NULL;
        $business_cta_btn_link_2_local = $is_set ? $country->quote_langs()->where('name', 'business_cta_btn_link_2_local')->first() : NULL;
        
        $personal_title_en = $is_set ? $country->quote_langs()->where('name', 'personal_title_en')->first() : NULL;
        $personal_title_local = $is_set ? $country->quote_langs()->where('name', 'personal_title_local')->first() : NULL;
        $personal_content_en = $is_set ? $country->quote_langs()->where('name', 'personal_content_en')->first() : NULL;
        $personal_content_local = $is_set ? $country->quote_langs()->where('name', 'personal_content_local')->first() : NULL;
        $personal_additional_list_en = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_en')->first() : NULL;
        $personal_additional_list_local = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_local')->first() : NULL;
        $personal_additional_list_value_en = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_value_en')->first() : NULL;
        $personal_additional_list_value_local = $is_set ? $country->quote_langs()->where('name', 'personal_additional_list_value_local')->first() : NULL;
        $personal_condition_list_en = $is_set ? $country->quote_langs()->where('name', 'personal_condition_list_en')->first() : NULL;
        $personal_condition_list_local = $is_set ? $country->quote_langs()->where('name', 'personal_condition_list_local')->first() : NULL;
        $personal_cta_btn_text_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_en')->first() : NULL;
        $personal_cta_btn_text_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_local')->first() : NULL;
        $personal_cta_btn_link_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_link_en')->first() : NULL;
        $personal_cta_btn_link_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_link_local')->first() : NULL;
        $personal_cta_btn_text_2_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_2_en')->first() : NULL;
        $personal_cta_btn_text_2_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_text_2_local')->first() : NULL;
        $personal_cta_btn_2_link_en = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_2_link_en')->first() : NULL;
        $personal_cta_btn_2_link_local = $is_set ? $country->quote_langs()->where('name', 'personal_cta_btn_2_link_local')->first() : NULL;
        $personal_caption_en = $is_set ? $country->quote_langs()->where('name', 'personal_caption_en')->first() : NULL;
        $personal_caption_local = $is_set ? $country->quote_langs()->where('name', 'personal_caption_local')->first() : NULL;

        $footer_en = $is_set ? $country->quote_langs()->where('name', 'footer_en')->first() : NULL;
        $footer_local = $is_set ? $country->quote_langs()->where('name', 'footer_local')->first() : NULL;


        //Define Business section Additional List in EN and Local Lang
        $business_additionals_en = [];
        $business_additionals_local = [];

        if ($business_additional_list_local && $business_additional_list_value_local) {
            $business_additionals_local = array_map(function ($name, $value) {
                return ['name' => $name, 'value' => $value];
            }, explode(" || ", $business_additional_list_local->description), explode(" || ", $business_additional_list_value_local->description));
        }

        if ($business_additional_list_en && $business_additional_list_value_en) {
            $business_additionals_en = array_map(function ($name, $value) {
                return ['name' => $name, 'value' => $value];
            }, explode(" || ", $business_additional_list_en->description), explode(" || ", $business_additional_list_value_en->description));
        }

        foreach($country->replace_fields() as $field){
            $data = $request->all();

            $data['receiver_country'] = $receiver->name;
            $data['transit_day'] = $receiver->transit_day;

            if($business_content_en){
                if(isset($data[$field])){
                    $business_content_en->description = str_replace('{%'.$field.'%}', $data[$field], $business_content_en->description);
                }
                $business_content_en->description = $this->replaceStaticString($country, $business_content_en->description, $data);
                $business_content_en->description = $this->styleSentence($business_content_en->description);
            }

            if($business_content_local){
                if(isset($data[$field])){
                    $business_content_local->description = str_replace('{%'.$field.'%}', $data[$field], $business_content_local->description);
                }
                $business_content_local->description = $this->replaceStaticString($country, $business_content_local->description, $data);
                $business_content_local->description = $this->styleSentence($business_content_local->description);
            }

            if($personal_content_en){
                if(isset($data[$field])){
                    $personal_content_en->description = str_replace('{%'.$field.'%}', $data[$field], $personal_content_en->description);
                }
                $personal_content_en->description = $this->replaceStaticString($country, $personal_content_en->description, $data);
                $personal_content_en->description = $this->styleSentence($personal_content_en->description);
            }

            if($personal_content_local){
                if(isset($data[$field])){
                    $personal_content_local->description = str_replace('{%'.$field.'%}', $data[$field], $personal_content_local->description);
                }
                $personal_content_local->description = $this->replaceStaticString($country, $personal_content_local->description, $data);
                $personal_content_local->description = $this->styleSentence($personal_content_local->description);
            }
        } 

        //Define Personal section Additional List in EN and Local Lang
        $personal_additionals_en = [];
        $personal_additionals_local = [];

        if ($personal_additional_list_local && $personal_additional_list_value_local) {
            $personal_additionals_local = array_map(function ($name, $value) {
                return ['name' => $name, 'value' => $value];
            }, explode(" || ", $personal_additional_list_local->description), explode(" || ", $personal_additional_list_value_local->description));
        }

        if ($personal_additional_list_en && $personal_additional_list_value_en) {
            $personal_additionals_en = array_map(function ($name, $value) {
                return ['name' => $name, 'value' => $value];
            }, explode(" || ", $personal_additional_list_en->description), explode(" || ", $personal_additional_list_value_en->description));
        }

        // Sanitize all output that berasal dari input user
        $sanitize = function($value) {
            return is_string($value) ? e($value) : $value;
        };

        $safe = function($arr) use ($sanitize) {
            if (is_array($arr)) {
                foreach ($arr as $k => $v) {
                    $arr[$k] = is_array($v) ? call_user_func(__FUNCTION__, $v) : $sanitize($v);
                }
            }
            return $arr;
        };

        return response()->success('Successfully calculate rate', $safe([
            'rates' => [
                'personal' => $personal ? number_format($personal, $country->decimal_places, '.', ',').$country->symbol_after_personal_price : 0,
                'business' => $business ? number_format($business, $country->decimal_places, '.', ',').$country->symbol_after_business_price : 0,
                'original' => $original ? number_format($original, $country->decimal_places, '.', ',') : 0,
            ],
            'currency_code' => $country->currency_code,
            'langs' => [
                'business' => [
                    'title_en' => $business_title_en ? $sanitize($business_title_en->description) : NULL,
                    'title_local' => $business_title_local ? $sanitize($business_title_local->description) : NULL,
                    'content_en' => $business_content_en ? $sanitize($business_content_en->description) : NULL,
                    'content_local' => $business_content_local ? $sanitize($business_content_local->description) : NULL,

                    'additional_list_en' => $safe($business_additionals_en),
                    'additional_list_local' => $safe($business_additionals_local),

                    'condition_list_en' => $business_condition_list_en ? array_map($sanitize, explode(" || ", $business_condition_list_en->description)) : NULL,
                    'condition_list_local' => $business_condition_list_local ? array_map($sanitize, explode(" || ", $business_condition_list_local->description)) : NULL,
                    'cta_btn_text_en' => $business_cta_btn_text_en ? $sanitize($business_cta_btn_text_en->description) : NULL,
                    'cta_btn_text_local' => $business_cta_btn_text_local ? $sanitize($business_cta_btn_text_local->description) : NULL,
                    'cta_btn_link_en' => $business_cta_btn_link_en ? $sanitize($business_cta_btn_link_en->description) : NULL,
                    'cta_btn_link_local' => $business_cta_btn_link_local ? $sanitize($business_cta_btn_link_local->description) : NULL,

                    'cta_btn_text_2_en' => $business_cta_btn_text_2_en ? $sanitize($business_cta_btn_text_2_en->description) : NULL,
                    'cta_btn_text_2_local' => $business_cta_btn_text_2_local ? $sanitize($business_cta_btn_text_2_local->description) : NULL,
                    'cta_btn_link_2_en' => $business_cta_btn_link_2_en ? $sanitize($business_cta_btn_link_2_en->description) : NULL,
                    'cta_btn_link_2_local' => $business_cta_btn_link_2_local ? $sanitize($business_cta_btn_link_2_local->description) : NULL,
                ],
                'personal' => [
                    'title_en' => $personal_title_en ? $sanitize($personal_title_en->description) : NULL,
                    'title_local' => $personal_title_local ? $sanitize($personal_title_local->description) : NULL,
                    'content_en' => $personal_content_en ? $sanitize($personal_content_en->description) : NULL,
                    'content_local' => $personal_content_local ? $sanitize($personal_content_local->description) : NULL,

                    'additional_list_en' => $safe($personal_additionals_en),
                    'additional_list_local' => $safe($personal_additionals_local),

                    'condition_list_en' => $personal_condition_list_en ? array_map($sanitize, explode(" || ", $personal_condition_list_en->description)) : NULL,
                    'condition_list_local' => $personal_condition_list_local ? array_map($sanitize, explode(" || ", $personal_condition_list_local->description)) : NULL,
                    'cta_btn_text_en' => $personal_cta_btn_text_en ? $sanitize($personal_cta_btn_text_en->description) : NULL,
                    'cta_btn_text_local' => $personal_cta_btn_text_local ? $sanitize($personal_cta_btn_text_local->description) : NULL,
                    'cta_btn_link_en' => $personal_cta_btn_link_en ? $sanitize($personal_cta_btn_link_en->description) : NULL,
                    'cta_btn_link_local' => $personal_cta_btn_link_local ? $sanitize($personal_cta_btn_link_local->description) : NULL,
                    'cta_btn_text_2_en' => $personal_cta_btn_text_2_en ? $sanitize($personal_cta_btn_text_2_en->description) : NULL,
                    'cta_btn_text_2_local' => $personal_cta_btn_text_2_local ? $sanitize($personal_cta_btn_text_2_local->description) : NULL,
                    'cta_btn_2_link_en' => $personal_cta_btn_2_link_en ? $sanitize($personal_cta_btn_2_link_en->description) : NULL,
                    'cta_btn_2_link_local' => $personal_cta_btn_2_link_local ? $sanitize($personal_cta_btn_2_link_local->description) : NULL,
                    'caption_en' => $personal_caption_en ? $sanitize($personal_caption_en->description) : NULL,
                    'caption_local' => $personal_caption_local ? $sanitize($personal_caption_local->description) : NULL,
                ],
                'footer' => [
                    'en' => $footer_en ? $sanitize($footer_en->description) : NULL,
                    'local' => $footer_local ? $sanitize($footer_local->description) : NULL,
                ]
            ]
        ]));
    }

    public function replaceStaticString($country, $content, $data)
    {
        foreach($country->static_fields() as $field){
            if(isset($data[$field])){
                $content = str_replace('{%'.$field.'%}', $data[$field], $content);
            }
        }

        return $content;
    }

    public function styleSentence($content)
    {
        //detect if in content has specific words
        $content = str_replace('&lt;font-color:red&gt;', '<div style="color:red">', $content);
        $content = str_replace('&lt;/font-color:red&gt;', '</div>', $content);

        return $content;
    }
}