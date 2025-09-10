<?php

namespace Tests\Unit\Database;

use Tests\TestCase;
use Database\Factories\UserFactory;
use Database\Factories\CountryFactory;

class DatabaseTest extends TestCase
{
    /**
     * Test database factories exist
     */
    public function test_database_factories_exist()
    {
        $this->assertTrue(class_exists(UserFactory::class));
        $this->assertTrue(class_exists(CountryFactory::class));
    }

    /**
     * Test factory methods exist
     */
    public function test_factory_methods_exist()
    {
        $userFactory = new UserFactory();
        $countryFactory = new CountryFactory();
        
        $this->assertTrue(method_exists($userFactory, 'definition'));
        $this->assertTrue(method_exists($countryFactory, 'definition'));
    }

    /**
     * Test factory definitions return arrays
     */
    public function test_factory_definitions_return_arrays()
    {
        $userFactory = new UserFactory();
        $countryFactory = new CountryFactory();
        
        $this->assertIsArray($userFactory->definition());
        $this->assertIsArray($countryFactory->definition());
    }

    /**
     * Test seeders exist
     */
    public function test_seeders_exist()
    {
        $this->assertTrue(class_exists(\Database\Seeders\CountriesTableSeeder::class));
    }
}
