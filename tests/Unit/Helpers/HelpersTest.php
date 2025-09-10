<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * Test Laravel helper functions exist
     */
    public function test_laravel_helper_functions_exist()
    {
        $this->assertTrue(function_exists('app'));
        $this->assertTrue(function_exists('config'));
        $this->assertTrue(function_exists('env'));
        $this->assertTrue(function_exists('route'));
        $this->assertTrue(function_exists('url'));
        $this->assertTrue(function_exists('asset'));
        $this->assertTrue(function_exists('view'));
        $this->assertTrue(function_exists('trans'));
        $this->assertTrue(function_exists('__'));
    }

    /**
     * Test helper functions return expected types
     */
    public function test_helper_functions_return_types()
    {
        $this->assertInstanceOf(\Illuminate\Foundation\Application::class, app());
        $this->assertTrue(is_string(config('app.name')) || is_null(config('app.name')));
        $this->assertTrue(is_string(env('APP_NAME', 'Laravel')) || is_null(env('APP_NAME')));
    }

    /**
     * Test URL helpers
     */
    public function test_url_helpers()
    {
        $this->assertIsString(url('/'));
        $this->assertIsString(asset(''));
    }
}
