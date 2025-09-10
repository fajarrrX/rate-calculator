<?php

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Rate;
use Tests\TestCase;

class RateTest extends TestCase
{
    /**
     * Test Rate model fillable attributes
     */
    public function test_rate_fillable_attributes()
    {
        $rate = new Rate();
        
        $expectedFillable = [
            'country_id',
            'package_type',
            'weight',
            'zone',
            'type',
            'price',
        ];
        
        $this->assertEquals($expectedFillable, $rate->getFillable());
    }

    /**
     * Test Rate model name constant
     */
    public function test_rate_name_constant()
    {
        $this->assertEquals('Rate', Rate::NAME);
    }

    /**
     * Test Rate country_id is in fillable attributes
     */
    public function test_rate_country_id_in_fillable()
    {
        $rate = new Rate();
        
        $fillable = $rate->getFillable();
        
        $this->assertContains('country_id', $fillable);
    }

    /**
     * Test Rate model can be instantiated
     */
    public function test_rate_model_instantiation()
    {
        $rate = new Rate();
        
        $this->assertInstanceOf(Rate::class, $rate);
        $this->assertTrue(method_exists($rate, 'country'));
    }
}
