<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CountryQuoteLang;
use App\Models\Rate;
use App\Models\RatecardFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:countries,code',
            // 'business_title_en' => 'required',
            // 'business_cta_btn_text_en' => 'required',
            // 'business_cta_btn_link' => 'required',

            // 'personal_title_en' => 'required',
            // 'personal_cta_btn_text_en' => 'required',
            // 'personal_cta_btn_link' => 'required',
        ]);

        if($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        try {
            DB::beginTransaction();

            $country = Country::create([
                'name' => $request->name,
                'code' => $request->code,
                'currency_code' => $request->currency_code,
                'decimal_places' => $request->decimal_places,
                'symbol_after_business_price' => $request->symbol_after_business_price,
                'symbol_after_personal_price' => $request->symbol_after_personal_price,
                'hide_package_opt_en' => $request->hide_package_opt_en ?? 0,
                'hide_package_opt_local' => $request->hide_package_opt_local ?? 0,
                'hide_step_1' => $request->hide_step_1 ?? 0,
                'share_country_id' => $request->share_rates_with_country ?? null
            ]);
    
            $valid_requests = [];
            $data = $request->all();
    
            foreach($country->valid_fields() as $field){
                if(isset($data[$field])){
                    $valid_requests[$field] = $data[$field];
                }
            }
    
            foreach($valid_requests as $key => $value){
    
                $lang = NULL;
                if(strpos($key, '_local') !== false){
                    $lang = 'local';
                }
    
                if(strpos($key, '_en') !== false){
                    $lang = 'en';
                }
    
                $country->quote_langs()->create([
                    'name' => $key,
                    'lang' => $lang,
                    'description' => $value
                ]);
            }

            DB::commit();
    
            return redirect()->route('home')->with('success', $this->successMessage('created', Country::NAME));

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $country = Country::with(['rates', 'receivers'])->findOrFail($id);
            $ratecards = RatecardFile::where('country_id', $id)->orderBy('created_at', 'desc')->get();

            return view('country.show', compact('country', 'ratecards'));
        } catch (Exception $e){
            return redirect()->back()->with('error', Country::NAME . ' not found!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $country = Country::findOrFail($id);
            return view('country.edit', compact('country'));
        } catch (Exception $e){
            return redirect()->back()->with('error', Country::NAME . ' not found!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required|unique:countries,code,'.$id,
                // 'business_title_en' => 'required',
                // 'business_cta_btn_text_en' => 'required',
                // 'business_cta_btn_link_en' => 'required',

                // 'personal_title_en' => 'required',
                // 'personal_cta_btn_text_en' => 'required',
                // 'personal_cta_btn_link_en' => 'required',
            ]);
            
            if($validation->fails()) {
                return redirect()->back()->withErrors($validation->errors());
            }

            DB::beginTransaction();
            
            $country = Country::findOrFail($id);
            $country->update([
                'name' => $request->name,
                'currency_code' => $request->currency_code,
                'decimal_places' => $request->decimal_places,
                'symbol_after_business_price' => $request->symbol_after_business_price,
                'symbol_after_personal_price' => $request->symbol_after_personal_price,
                'hide_package_opt_en' => $request->hide_package_opt_en ?? 0,
                'hide_package_opt_local' => $request->hide_package_opt_local ?? 0,
                'hide_step_1' => $request->hide_step_1 ?? 0,
                'share_country_id' => $request->share_rates_with_country ?? null
            ]);

            $valid_requests = [];
            $data = $request->all();

            foreach($country->valid_fields() as $field){
                $valid_requests[$field] = $data[$field];
            }

            foreach($valid_requests as $key => $value){

                $lang = NULL;
                if(strpos($key, '_local') !== false){
                    $lang = 'local';
                }

                if(strpos($key, '_en') !== false){
                    $lang = 'en';
                }

                $country->quote_langs()->updateOrCreate([
                    'name' => $key,
                    'lang' => $lang,
                ],[
                    'description' => $value
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', $this->successMessage('updated', Country::NAME));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', Country::NAME . ' not found!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $country = Country::findOrFail($id);
            $country->delete();
            return redirect()->route('home')->with('success', $this->successMessage('deleted', Country::NAME));

        } catch (Exception $e) {
            return redirect()->back()->with('error', Country::NAME . ' not found!');
        }
    }

    /**
     * Show the rates of the specified country.
     *
     **/
    public function rates(Country $country, Request $request)
    {
        $rates = collect();
        $highest_zone = 0;

        if($request->type){
            $rates = $country->rates()->where('type', $request->type ?? RateType::Original)->orderBy('weight')->get();
            $highest_zone = $country->rates()->where('type', $request->type ?? RateType::Original)->max('zone');
        }

        return view('country.rates', compact('country', 'rates', 'highest_zone'));
    }

    /**
     * Show receiver list of specified country.
     *
     **/
    public function receivers(Country $country)
    {
        $receivers = $country->receivers()->orderBy('name')->get();
        return view('country.receivers', compact('country', 'receivers'));
    }
}
