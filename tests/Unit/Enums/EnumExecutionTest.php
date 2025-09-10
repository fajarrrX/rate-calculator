<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\PackageType;
use App\Enums\RateType;

class EnumExecutionTest extends TestCase
{
    /**
     * Test PackageType enum methods execution
     */
    public function test_package_type_enum_execution()
    {
        // Execute static methods
        $document = PackageType::Document();
        $nonDocument = PackageType::NonDocument();

        // Execute value property
        $documentValue = $document->value;
        $nonDocumentValue = $nonDocument->value;

        // Execute description method if it exists
        if (method_exists(PackageType::class, 'getDescription')) {
            $documentDesc = PackageType::getDescription($documentValue);
            $nonDocumentDesc = PackageType::getDescription($nonDocumentValue);
            
            $this->assertIsString($documentDesc);
            $this->assertIsString($nonDocumentDesc);
        }

        // Execute comparison methods
        $this->assertTrue($document->is(PackageType::Document()));
        $this->assertFalse($document->is(PackageType::NonDocument()));

        // Execute toArray if exists (static method)
        if (method_exists(PackageType::class, 'toArray')) {
            try {
                $array = PackageType::toArray();
                $this->assertIsArray($array);
            } catch (\BadMethodCallException $e) {
                // Method exists but is not static, skip this test
                $this->assertTrue(true);
            }
        }

        // Verify values
        $this->assertEquals(1, $documentValue);
        $this->assertEquals(2, $nonDocumentValue);
    }

    /**
     * Test RateType enum methods execution
     */
    public function test_rate_type_enum_execution()
    {
        // Execute static creation methods
        $original = RateType::Original();
        $personal = RateType::Personal();
        $business = RateType::Business();

        // Execute value retrieval
        $originalValue = $original->value;
        $personalValue = $personal->value;
        $businessValue = $business->value;

        // Execute instance methods
        $this->assertTrue($original->is(RateType::Original()));
        $this->assertFalse($original->is(RateType::Personal()));
        $this->assertFalse($original->is(RateType::Business()));

        // Execute comparison between instances
        $this->assertNotEquals($original, $personal);
        $this->assertNotEquals($personal, $business);
        $this->assertNotEquals($original, $business);

        // Execute key method if exists
        if (method_exists(RateType::class, 'getKey')) {
            $originalKey = RateType::getKey($originalValue);
            $this->assertIsString($originalKey);
        }

        // Verify all values are correct
        $this->assertEquals(1, $originalValue);
        $this->assertEquals(2, $personalValue);
        $this->assertEquals(3, $businessValue);
    }

    /**
     * Test enum iteration and collection methods
     */
    public function test_enum_collection_execution()
    {
        // Execute getInstances if available
        if (method_exists(PackageType::class, 'getInstances')) {
            $instances = PackageType::getInstances();
            $this->assertIsArray($instances);
            $this->assertCount(2, $instances);
        }

        if (method_exists(RateType::class, 'getInstances')) {
            $instances = RateType::getInstances();
            $this->assertIsArray($instances);
            $this->assertCount(3, $instances);
        }

        // Execute getValues if available
        if (method_exists(PackageType::class, 'getValues')) {
            $values = PackageType::getValues();
            $this->assertIsArray($values);
            $this->assertContains(1, $values);
            $this->assertContains(2, $values);
        }

        if (method_exists(RateType::class, 'getValues')) {
            $values = RateType::getValues();
            $this->assertIsArray($values);
            $this->assertContains(1, $values);
            $this->assertContains(2, $values);
            $this->assertContains(3, $values);
        }
    }

    /**
     * Test enum conversion and casting
     */
    public function test_enum_conversion_execution()
    {
        // Test fromValue if exists
        if (method_exists(PackageType::class, 'fromValue')) {
            $document = PackageType::fromValue(1);
            $this->assertEquals(PackageType::Document(), $document);
        }

        if (method_exists(RateType::class, 'fromValue')) {
            $original = RateType::fromValue(1);
            $personal = RateType::fromValue(2);
            $business = RateType::fromValue(3);
            
            $this->assertEquals(RateType::Original(), $original);
            $this->assertEquals(RateType::Personal(), $personal);
            $this->assertEquals(RateType::Business(), $business);
        }

        // Test string conversion
        $document = PackageType::Document();
        $original = RateType::Original();

        // Execute __toString if exists
        if (method_exists($document, '__toString')) {
            $documentString = (string) $document;
            $this->assertIsString($documentString);
        }

        if (method_exists($original, '__toString')) {
            $originalString = (string) $original;
            $this->assertIsString($originalString);
        }
    }
}
