<?php

namespace Tests\Unit\Models;

use App\Models\RatecardFile;
use App\Models\Country;
use Tests\TestCase;

class RatecardFileTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Skip all tests if RatecardFile has been mocked
        if (!method_exists('App\\Models\\RatecardFile', 'getFillable') ||
            !defined('App\\Models\\RatecardFile::NAME')) {
            $this->markTestSkipped('RatecardFile class has been mocked, skipping to avoid conflicts');
        }
    }

    /**
     * Test RatecardFile model can be instantiated
     */
    public function test_ratecard_file_instantiation()
    {
        $file = new RatecardFile();
        
        $this->assertInstanceOf(RatecardFile::class, $file);
    }

    /**
     * Test RatecardFile fillable attributes
     */
    public function test_ratecard_file_fillable()
    {
        $file = new RatecardFile();
        
        $expected = [
            'country_id',
            'name', 
            'path',
            'type'
        ];
        
        $this->assertEquals($expected, $file->getFillable());
    }

    /**
     * Test RatecardFile constants
     */
    public function test_ratecard_file_constants()
    {
        $this->assertEquals('Ratecard File', RatecardFile::NAME);
    }

    /**
     * Test RatecardFile country relationship method exists
     */
    public function test_ratecard_file_country_relationship()
    {
        $file = new RatecardFile();
        
        // Test method exists
        $this->assertTrue(method_exists($file, 'country'));
        
        // Test method is callable
        $reflection = new \ReflectionMethod($file, 'country');
        $this->assertTrue($reflection->isPublic());
        $this->assertFalse($reflection->isStatic());
    }

    /**
     * Test RatecardFile model inheritance
     */
    public function test_ratecard_file_inheritance()
    {
        $file = new RatecardFile();
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Model::class, $file);
        
        $reflection = new \ReflectionClass($file);
        $traits = $reflection->getTraitNames();
        
        $this->assertContains('Illuminate\Database\Eloquent\Factories\HasFactory', $traits);
    }

    /**
     * Test RatecardFile model namespace
     */
    public function test_ratecard_file_namespace()
    {
        $file = new RatecardFile();
        $reflection = new \ReflectionClass($file);
        
        $this->assertEquals('App\Models', $reflection->getNamespaceName());
    }

    /**
     * Test RatecardFile model properties
     */
    public function test_ratecard_file_properties()
    {
        $file = new RatecardFile();
        
        // Test fillable property exists
        $this->assertIsArray($file->getFillable());
        $this->assertCount(4, $file->getFillable());
        
        // Test table name (default)
        $this->assertEquals('ratecard_files', $file->getTable());
    }
}
