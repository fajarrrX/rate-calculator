<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use App\Enums\PackageType;
use App\Enums\RateType;

class EnumIntegrationTest extends TestCase
{
    /**
     * Test PackageType enum functionality
     */
    public function test_package_type_enum_usage()
    {
        // Test enum values using BenSampo enum methods
        $document = PackageType::Document();
        $nonDocument = PackageType::NonDocument();
        
        $this->assertNotNull($document);
        $this->assertNotNull($nonDocument);
        $this->assertEquals(1, $document->value);
        $this->assertEquals(2, $nonDocument->value);

        // Test enum methods if available
        if (method_exists(PackageType::class, 'getValues')) {
            $values = PackageType::getValues();
            $this->assertIsArray($values);
        }

        if (method_exists(PackageType::class, 'getKeys')) {
            $keys = PackageType::getKeys();
            $this->assertIsArray($keys);
        }

        // Test enum instantiation
        if (method_exists(PackageType::class, 'getInstance')) {
            $instance = PackageType::getInstance($document);
            $this->assertInstanceOf(PackageType::class, $instance);
        }
    }

    /**
     * Test RateType enum functionality
     */
    public function test_rate_type_enum_usage()
    {
        // Test enum methods using BenSampo enum
        $original = RateType::Original();
        $personal = RateType::Personal();
        $business = RateType::Business();
        
        $this->assertEquals(1, $original->value);
        $this->assertEquals(2, $personal->value);
        $this->assertEquals(3, $business->value);
        
        // Test enum comparisons
        $this->assertTrue($original->is(RateType::Original()));
        $this->assertFalse($original->is(RateType::Personal()));
        $this->assertFalse($original->is(RateType::Business()));

        // Test enum methods if available
        if (method_exists(RateType::class, 'getValues')) {
            $values = RateType::getValues();
            $this->assertIsArray($values);
            $this->assertContains($standard, $values);
        }

        if (method_exists(RateType::class, 'getKeys')) {
            $keys = RateType::getKeys();
            $this->assertIsArray($keys);
        }
    }

    /**
     * Test enum usage in context
     */
    public function test_enum_practical_usage()
    {
        // Test that enums can be used in comparisons using BenSampo enum
        $packageType = PackageType::Document();
        
        if ($packageType->is(PackageType::Document())) {
            $this->assertTrue(true);
        } else {
            $this->fail('Enum comparison failed');
        }

        // Test rate type usage with actual enum methods
        $rateType = RateType::Original();
        
        $this->assertTrue($rateType->is(RateType::Original()));
        $this->assertFalse($rateType->is(RateType::Personal()));
        $this->assertFalse($rateType->is(RateType::Business()));
    }
}
