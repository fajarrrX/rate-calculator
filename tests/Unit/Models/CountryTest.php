<?php

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\CountryQuoteLang;
use App\Models\CountryZone;
use App\Models\Rate;
use Tests\TestCase;

class CountryTest extends TestCase
{
    /**
     * Test Country model fillable attributes
     */
    public function test_country_fillable_attributes()
    {
        $country = new Country();
        
        $expectedFillable = [
            'name', 'code', 'currency_code', 'decimal_places', 
            'symbol_after_personal_price', 'symbol_after_business_price', 
            'hide_package_opt_en', 'hide_package_opt_local', 
            'hide_step_1', 'share_country_id'
        ];
        
        $this->assertEquals($expectedFillable, $country->getFillable());
    }

    /**
     * Test Country model name constant
     */
    public function test_country_name_constant()
    {
        $this->assertEquals('Country', Country::NAME);
    }

    /**
     * Test Country share_country_id attribute exists
     */
    public function test_country_share_country_id_in_fillable()
    {
        $country = new Country();
        
        $fillable = $country->getFillable();
        
        $this->assertContains('share_country_id', $fillable);
    }

    /**
     * Test Country model can be instantiated
     */
    public function test_country_model_instantiation()
    {
        $country = new Country();
        
        $this->assertInstanceOf(Country::class, $country);
        $this->assertTrue(method_exists($country, 'rates'));
        $this->assertTrue(method_exists($country, 'receivers'));
        $this->assertTrue(method_exists($country, 'share_country'));
        $this->assertTrue(method_exists($country, 'quote_langs'));
        $this->assertTrue(method_exists($country, 'valid_fields'));
    }

    /**
     * Test Country valid_fields method
     */
    public function test_country_valid_fields_method()
    {
        $country = new Country();
        
        $validFields = $country->valid_fields();
        
        $this->assertIsArray($validFields);
        $this->assertNotEmpty($validFields);
    }
}
