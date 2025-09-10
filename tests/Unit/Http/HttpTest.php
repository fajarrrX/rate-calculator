<?php

namespace Tests\Unit\Http;

use App\Http\Controllers\Controller;
use App\Http\Kernel;
use Tests\TestCase;

class HttpTest extends TestCase
{
    /**
     * Test base Controller exists
     */
    public function test_base_controller_exists()
    {
        $controller = new Controller();
        
        $this->assertInstanceOf(Controller::class, $controller);
    }

    /**
     * Test HTTP Kernel exists
     */
    public function test_http_kernel_exists()
    {
        $kernel = app(Kernel::class);
        
        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    /**
     * Test HTTP Kernel has middleware
     */
    public function test_http_kernel_has_middleware()
    {
        $kernel = app(Kernel::class);
        
        // Using reflection to access protected properties
        $reflection = new \ReflectionClass($kernel);
        $middlewareProperty = $reflection->getProperty('middleware');
        $middlewareProperty->setAccessible(true);
        $middleware = $middlewareProperty->getValue($kernel);
        
        $this->assertIsArray($middleware);
        $this->assertNotEmpty($middleware);
    }

    /**
     * Test HTTP Kernel has route middleware
     */
    public function test_http_kernel_has_route_middleware()
    {
        $kernel = app(Kernel::class);
        
        // Using reflection to access protected properties
        $reflection = new \ReflectionClass($kernel);
        $routeMiddlewareProperty = $reflection->getProperty('routeMiddleware');
        $routeMiddlewareProperty->setAccessible(true);
        $routeMiddleware = $routeMiddlewareProperty->getValue($kernel);
        
        $this->assertIsArray($routeMiddleware);
        $this->assertArrayHasKey('auth', $routeMiddleware);
        $this->assertArrayHasKey('guest', $routeMiddleware);
    }
}
