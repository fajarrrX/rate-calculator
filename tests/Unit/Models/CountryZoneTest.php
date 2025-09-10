<?php

namespace Tests\Unit\Models;

use App\Models\CountryZone;
use Tests\TestCase;

class CountryZoneTest extends TestCase
{
    /**
     * Test CountryZone model fillable attributes
     */
    public function test_country_zone_fillable_attributes()
    {
        $zone = new CountryZone();
        
        $expectedFillable = [
            'country_id',
            'name',
            'zone',
            'transit_day'
        ];
        
        $this->assertEquals($expectedFillable, $zone->getFillable());
    }

    /**
     * Test CountryZone model instantiation
     */
    public function test_country_zone_instantiation()
    {
        $zone = new CountryZone();
        
        $this->assertInstanceOf(CountryZone::class, $zone);
        $this->assertTrue(method_exists($zone, 'country'));
    }

    /**
     * Test CountryZone has country_id in fillable
     */
    public function test_country_zone_country_id_in_fillable()
    {
        $zone = new CountryZone();
        
        $fillable = $zone->getFillable();
        
        $this->assertContains('country_id', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('zone', $fillable);
        $this->assertContains('transit_day', $fillable);
    }
}
