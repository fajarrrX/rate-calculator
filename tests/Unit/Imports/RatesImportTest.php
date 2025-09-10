<?php

namespace Tests\Unit\Imports;

use App\Imports\RatesImport;
use App\Models\Country;
use Tests\TestCase;

class RatesImportTest extends TestCase
{
    /**
     * Test RatesImport constructor sets properties
     */
    public function test_rates_import_constructor()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'personal';
        
        $import = new RatesImport($country, $type);
        
        $this->assertInstanceOf(RatesImport::class, $import);
        $this->assertTrue(method_exists($import, 'sheets'));
    }

    /**
     * Test RatesImport sheets method returns array
     */
    public function test_rates_import_sheets_method()
    {
        $country = new Country(['name' => 'Test Country', 'code' => 'TC']);
        $type = 'personal';
        
        $import = new RatesImport($country, $type);
        $sheets = $import->sheets();
        
        $this->assertIsArray($sheets);
        $this->assertArrayHasKey('Documents', $sheets);
        $this->assertArrayHasKey('Non Documents', $sheets);
        $this->assertArrayHasKey('Zones', $sheets);
    }
}
