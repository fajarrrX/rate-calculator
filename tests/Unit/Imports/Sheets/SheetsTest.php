<?php

namespace Tests\Unit\Imports\Sheets;

use App\Imports\Sheets\Document;
use App\Imports\Sheets\Parcel;
use App\Imports\Sheets\Zone;
use App\Models\Country;
use Tests\TestCase;

class SheetsTest extends TestCase
{
    /**
     * Test Document sheet import class exists
     */
    public function test_document_sheet_exists()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'personal';
        
        $sheet = new Document($country, $type);
        
        $this->assertInstanceOf(Document::class, $sheet);
        $this->assertTrue(method_exists($sheet, 'collection'));
    }

    /**
     * Test Parcel sheet import class exists
     */
    public function test_parcel_sheet_exists()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'personal';
        
        $sheet = new Parcel($country, $type);
        
        $this->assertInstanceOf(Parcel::class, $sheet);
        $this->assertTrue(method_exists($sheet, 'collection'));
    }

    /**
     * Test Zone sheet import class exists
     */
    public function test_zone_sheet_exists()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        
        $sheet = new Zone($country);
        
        $this->assertInstanceOf(Zone::class, $sheet);
        $this->assertTrue(method_exists($sheet, 'collection'));
    }

    /**
     * Test Document constructor sets properties
     */
    public function test_document_constructor()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'business';
        
        $sheet = new Document($country, $type);
        
        // Using reflection to test protected properties
        $reflection = new \ReflectionClass($sheet);
        $countryProperty = $reflection->getProperty('country');
        $countryProperty->setAccessible(true);
        $typeProperty = $reflection->getProperty('type');
        $typeProperty->setAccessible(true);
        
        $this->assertEquals($country, $countryProperty->getValue($sheet));
        $this->assertEquals($type, $typeProperty->getValue($sheet));
    }

    /**
     * Test Parcel constructor sets properties
     */
    public function test_parcel_constructor()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'business';
        
        $sheet = new Parcel($country, $type);
        
        // Using reflection to test protected properties
        $reflection = new \ReflectionClass($sheet);
        $countryProperty = $reflection->getProperty('country');
        $countryProperty->setAccessible(true);
        $typeProperty = $reflection->getProperty('type');
        $typeProperty->setAccessible(true);
        
        $this->assertEquals($country, $countryProperty->getValue($sheet));
        $this->assertEquals($type, $typeProperty->getValue($sheet));
    }

    /**
     * Test Zone constructor sets country property
     */
    public function test_zone_constructor()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        
        $sheet = new Zone($country);
        
        // Using reflection to test protected properties
        $reflection = new \ReflectionClass($sheet);
        $countryProperty = $reflection->getProperty('country');
        $countryProperty->setAccessible(true);
        
        $this->assertEquals($country, $countryProperty->getValue($sheet));
    }
}
