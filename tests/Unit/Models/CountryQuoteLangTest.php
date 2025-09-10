<?php

namespace Tests\Unit\Models;

use App\Models\CountryQuoteLang;
use Tests\TestCase;

class CountryQuoteLangTest extends TestCase
{
    /**
     * Test CountryQuoteLang model fillable attributes
     */
    public function test_country_quote_lang_fillable_attributes()
    {
        $model = new CountryQuoteLang();
        
        $expectedFillable = ['country_id', 'name', 'lang', 'description'];
        
        $this->assertEquals($expectedFillable, $model->getFillable());
    }

    /**
     * Test CountryQuoteLang model instantiation
     */
    public function test_country_quote_lang_instantiation()
    {
        $model = new CountryQuoteLang();
        
        $this->assertInstanceOf(CountryQuoteLang::class, $model);
        $this->assertTrue(method_exists($model, 'replace_fields'));
        $this->assertTrue(method_exists($model, 'static_fields'));
        $this->assertTrue(method_exists($model, 'valid_fields'));
    }

    /**
     * Test CountryQuoteLang replace_fields method
     */
    public function test_country_quote_lang_replace_fields()
    {
        $model = new CountryQuoteLang();
        
        $replaceFields = $model->replace_fields();
        
        $this->assertIsArray($replaceFields);
        $this->assertContains('receiver_working_days', $replaceFields);
        $this->assertContains('receiver_country_name', $replaceFields);
        $this->assertContains('promo_code', $replaceFields);
        $this->assertContains('promo_code_2', $replaceFields);
        $this->assertContains('promo_discount', $replaceFields);
    }

    /**
     * Test CountryQuoteLang static_fields method
     */
    public function test_country_quote_lang_static_fields()
    {
        $model = new CountryQuoteLang();
        
        $staticFields = $model->static_fields();
        
        $this->assertIsArray($staticFields);
        $this->assertContains('transit_day', $staticFields);
        $this->assertContains('receiver_country', $staticFields);
    }

    /**
     * Test CountryQuoteLang valid_fields method returns array
     */
    public function test_country_quote_lang_valid_fields()
    {
        $model = new CountryQuoteLang();
        
        $validFields = $model->valid_fields();
        
        $this->assertIsArray($validFields);
        $this->assertNotEmpty($validFields);
    }
}
