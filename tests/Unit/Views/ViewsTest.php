<?php

namespace Tests\Unit\Views;

use Tests\TestCase;
use Illuminate\Support\Facades\View;

class ViewsTest extends TestCase
{
    /**
     * Test view files exist
     */
    public function test_view_files_exist()
    {
        // Test if view files are accessible
        $this->assertTrue(View::exists('layouts.app'));
        $this->assertTrue(View::exists('auth.login'));
        $this->assertTrue(View::exists('auth.register'));
        $this->assertTrue(View::exists('home'));
    }

    /**
     * Test view facade functionality
     */
    public function test_view_facade_functionality()
    {
        $this->assertTrue(class_exists(\Illuminate\Support\Facades\View::class));
        $this->assertInstanceOf(\Illuminate\View\Factory::class, View::getFacadeRoot());
    }

    /**
     * Test view compilation
     */
    public function test_view_compilation()
    {
        // Test that views can be compiled without errors
        $view = View::make('layouts.app');
        $this->assertInstanceOf(\Illuminate\Contracts\View\View::class, $view);
    }
}
