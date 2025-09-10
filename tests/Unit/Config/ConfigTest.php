<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Test config files are accessible
     */
    public function test_config_files_accessible()
    {
        $this->assertIsArray(config('app'));
        $this->assertIsArray(config('auth'));
        $this->assertIsArray(config('database'));
        $this->assertIsArray(config('cache'));
        $this->assertIsArray(config('cors'));
        $this->assertIsArray(config('excel'));
        $this->assertIsArray(config('filesystems'));
        $this->assertIsArray(config('logging'));
        $this->assertIsArray(config('mail'));
        $this->assertIsArray(config('queue'));
        $this->assertIsArray(config('session'));
        $this->assertIsArray(config('view'));
    }

    /**
     * Test app configuration values
     */
    public function test_app_config_values()
    {
        $this->assertNotEmpty(config('app.name'));
        $this->assertNotEmpty(config('app.env'));
        $this->assertNotEmpty(config('app.key'));
        $this->assertTrue(in_array(config('app.env'), ['local', 'testing', 'production', 'staging']));
    }

    /**
     * Test database configuration
     */
    public function test_database_config()
    {
        $this->assertNotEmpty(config('database.default'));
        $this->assertIsArray(config('database.connections'));
        $this->assertTrue(array_key_exists(config('database.default'), config('database.connections')));
    }
}
