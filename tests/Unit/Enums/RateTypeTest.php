<?php

namespace Tests\Unit\Enums;

use App\Enums\RateType;
use Tests\TestCase;

class RateTypeTest extends TestCase
{
    /**
     * Test RateType enum constants
     */
    public function test_rate_type_constants()
    {
        $this->assertEquals(1, RateType::Original);
        $this->assertEquals(2, RateType::Personal);
        $this->assertEquals(3, RateType::Business);
    }

    /**
     * Test RateType enum values
     */
    public function test_rate_type_values()
    {
        $values = RateType::getValues();
        
        $this->assertContains(RateType::Original, $values);
        $this->assertContains(RateType::Personal, $values);
        $this->assertContains(RateType::Business, $values);
        $this->assertCount(3, $values);
    }

    /**
     * Test RateType enum keys
     */
    public function test_rate_type_keys()
    {
        $keys = RateType::getKeys();
        
        $this->assertContains('Original', $keys);
        $this->assertContains('Personal', $keys);
        $this->assertContains('Business', $keys);
        $this->assertCount(3, $keys);
    }

    /**
     * Test RateType instance creation
     */
    public function test_rate_type_instance_creation()
    {
        $originalType = new RateType(RateType::Original);
        $personalType = new RateType(RateType::Personal);
        $businessType = new RateType(RateType::Business);

        $this->assertEquals(RateType::Original, $originalType->value);
        $this->assertEquals(RateType::Personal, $personalType->value);
        $this->assertEquals(RateType::Business, $businessType->value);
    }

    /**
     * Test RateType key and value mapping
     */
    public function test_rate_type_key_value_mapping()
    {
        $this->assertEquals('Original', RateType::getKey(RateType::Original));
        $this->assertEquals('Personal', RateType::getKey(RateType::Personal));
        $this->assertEquals('Business', RateType::getKey(RateType::Business));
        
        $this->assertEquals(RateType::Original, RateType::getValue('Original'));
        $this->assertEquals(RateType::Personal, RateType::getValue('Personal'));
        $this->assertEquals(RateType::Business, RateType::getValue('Business'));
    }
}
