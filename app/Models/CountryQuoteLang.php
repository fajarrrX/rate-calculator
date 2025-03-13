<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryQuoteLang extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'name', 'lang', 'description'];

    public function replace_fields()
    {
        return [
            'receiver_working_days',
            'receiver_country_name',
            'promo_code',
            'promo_code_2',
            'promo_discount'
        ];
    }

    public function static_fields()
    {
        return [
            'transit_day',
            'receiver_country'
        ];
    }

    public function valid_fields()
    {
        return [
            'business_title_en',
            'business_title_local',
            'business_content_en',
            'business_content_local',
            'business_additional_list_en',
            'business_additional_list_local',
            'business_additional_list_value_en',
            'business_additional_list_value_local',
            'business_condition_list_en',
            'business_condition_list_local',
            'business_cta_btn_text_en',
            'business_cta_btn_text_local',
            'business_cta_btn_link_en',
            'business_cta_btn_link_local',
            'business_cta_btn_text_2_en',
            'business_cta_btn_text_2_local',
            'business_cta_btn_link_2_en',
            'business_cta_btn_link_2_local',
            'personal_title_en',
            'personal_title_local',
            'personal_content_en',
            'personal_content_local',
            'personal_additional_list_en',
            'personal_additional_list_local',
            'personal_cta_btn_text_en',
            'personal_cta_btn_text_local',
            'personal_cta_btn_text_2_en',
            'personal_cta_btn_text_2_local',
            'personal_additional_list_value_en',
            'personal_additional_list_value_local',
            'personal_condition_list_en',
            'personal_condition_list_local',
            'personal_cta_btn_link_en',
            'personal_cta_btn_link_local',
            'personal_cta_btn_2_link_en',
            'personal_cta_btn_2_link_local',
            'personal_caption_en',
            'personal_caption_local',
            'footer_en',
            'footer_local'
        ];
    }
}
