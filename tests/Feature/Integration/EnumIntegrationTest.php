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
        // Test enum values
        $document = PackageType::DOCUMENT;
        $parcel = PackageType::PARCEL;
        
        $this->assertNotNull($document);
        $this->assertNotNull($parcel);

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
        // Test enum constants exist
        $this->assertTrue(defined('App\Enums\RateType::STANDARD'));
        $this->assertTrue(defined('App\Enums\RateType::EXPRESS'));
        
        // Test enum values
        $standard = RateType::STANDARD;
        $express = RateType::EXPRESS;
        
        $this->assertNotNull($standard);
        $this->assertNotNull($express);

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
        // Test that enums can be used in comparisons
        $packageType = PackageType::DOCUMENT;
        
        if ($packageType === PackageType::DOCUMENT) {
            $this->assertTrue(true);
        } else {
            $this->fail('Enum comparison failed');
        }

        // Test rate type usage
        $rateType = RateType::STANDARD;
        
        $this->assertEquals(RateType::STANDARD, $rateType);
        $this->assertNotEquals(RateType::EXPRESS, $rateType);
    }
}
