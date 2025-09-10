<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Tests\TestCase;

class HandlerTest extends TestCase
{
    /**
     * Test Handler can be instantiated
     */
    public function test_exception_handler_exists()
    {
        $handler = new Handler(app());
        
        $this->assertInstanceOf(Handler::class, $handler);
        $this->assertTrue(method_exists($handler, 'render'));
        $this->assertTrue(method_exists($handler, 'register'));
    }

    /**
     * Test Handler has correct dontFlash properties
     */
    public function test_exception_handler_dont_flash_properties()
    {
        $handler = new Handler(app());
        
        // Using reflection to access protected property
        $reflection = new \ReflectionClass($handler);
        $dontFlashProperty = $reflection->getProperty('dontFlash');
        $dontFlashProperty->setAccessible(true);
        $dontFlash = $dontFlashProperty->getValue($handler);
        
        $this->assertIsArray($dontFlash);
        $this->assertContains('current_password', $dontFlash);
        $this->assertContains('password', $dontFlash);
        $this->assertContains('password_confirmation', $dontFlash);
    }

    /**
     * Test Handler has correct dontReport properties
     */
    public function test_exception_handler_dont_report_properties()
    {
        $handler = new Handler(app());
        
        // Using reflection to access protected property
        $reflection = new \ReflectionClass($handler);
        $dontReportProperty = $reflection->getProperty('dontReport');
        $dontReportProperty->setAccessible(true);
        $dontReport = $dontReportProperty->getValue($handler);
        
        $this->assertIsArray($dontReport);
    }
}
