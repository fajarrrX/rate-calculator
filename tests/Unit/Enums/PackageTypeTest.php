<?php

namespace Tests\Unit\Enums;

use App\Enums\PackageType;
use Tests\TestCase;

class PackageTypeTest extends TestCase
{
    /**
     * Test PackageType enum constants
     */
    public function test_package_type_constants()
    {
        $this->assertEquals(1, PackageType::Document);
        $this->assertEquals(2, PackageType::NonDocument);
    }

    /**
     * Test PackageType enum values
     */
    public function test_package_type_values()
    {
        $values = PackageType::getValues();
        
        $this->assertContains(PackageType::Document, $values);
        $this->assertContains(PackageType::NonDocument, $values);
        $this->assertCount(2, $values);
    }

    /**
     * Test PackageType enum keys
     */
    public function test_package_type_keys()
    {
        $keys = PackageType::getKeys();
        
        $this->assertContains('Document', $keys);
        $this->assertContains('NonDocument', $keys);
        $this->assertCount(2, $keys);
    }

    /**
     * Test PackageType instance creation
     */
    public function test_package_type_instance_creation()
    {
        $documentType = new PackageType(PackageType::Document);
        $nonDocumentType = new PackageType(PackageType::NonDocument);

        $this->assertEquals(PackageType::Document, $documentType->value);
        $this->assertEquals(PackageType::NonDocument, $nonDocumentType->value);
    }

    /**
     * Test PackageType key and value mapping
     */
    public function test_package_type_key_value_mapping()
    {
        $this->assertEquals('Document', PackageType::getKey(PackageType::Document));
        $this->assertEquals('NonDocument', PackageType::getKey(PackageType::NonDocument));
        
        $this->assertEquals(PackageType::Document, PackageType::getValue('Document'));
        $this->assertEquals(PackageType::NonDocument, PackageType::getValue('NonDocument'));
    }
}
