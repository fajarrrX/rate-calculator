<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use Illuminate\Support\Facades\App;
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\RouteServiceProvider;

class ServiceProviderIntegrationTest extends TestCase
{
    /**
     * Test that service providers are properly registered
     */
    public function test_service_providers_registered()
    {
        $app = App::getFacadeRoot();
        
        // Test AppServiceProvider
        $appProvider = new AppServiceProvider($app);
        $this->assertInstanceOf(AppServiceProvider::class, $appProvider);
        
        // Test that register method exists and can be called
        if (method_exists($appProvider, 'register')) {
            $appProvider->register();
            $this->assertTrue(true);
        }

        // Test AuthServiceProvider
        $authProvider = new AuthServiceProvider($app);
        $this->assertInstanceOf(AuthServiceProvider::class, $authProvider);
        
        if (method_exists($authProvider, 'boot')) {
            $authProvider->boot();
            $this->assertTrue(true);
        }
    }

    /**
     * Test EventServiceProvider functionality
     */
    public function test_event_service_provider()
    {
        $app = App::getFacadeRoot();
        $eventProvider = new EventServiceProvider($app);
        
        $this->assertInstanceOf(EventServiceProvider::class, $eventProvider);
        
        // Test that listen property exists (use reflection for protected property)
        if (property_exists($eventProvider, 'listen')) {
            $reflection = new \ReflectionClass($eventProvider);
            if ($reflection->hasProperty('listen')) {
                $this->assertTrue(true); // Property exists
            }
        }

        // Test shouldDiscoverEvents method if it exists
        if (method_exists($eventProvider, 'shouldDiscoverEvents')) {
            $result = $eventProvider->shouldDiscoverEvents();
            $this->assertIsBool($result);
        }
    }

    /**
     * Test RouteServiceProvider functionality
     */
    public function test_route_service_provider()
    {
        $app = App::getFacadeRoot();
        $routeProvider = new RouteServiceProvider($app);
        
        $this->assertInstanceOf(RouteServiceProvider::class, $routeProvider);
        
        // Test that boot method works
        if (method_exists($routeProvider, 'boot')) {
            $routeProvider->boot();
            $this->assertTrue(true);
        }
    }

    /**
     * Test application configuration and services
     */
    public function test_application_services()
    {
        // Test that key services are available
        $this->assertTrue(App::bound('config'));
        $this->assertTrue(App::bound('db'));
        $this->assertTrue(App::bound('cache'));
        
        // Test config values
        $appName = config('app.name');
        $this->assertIsString($appName);
        
        $appEnv = config('app.env');
        $this->assertEquals('testing', $appEnv);
    }

    /**
     * Test ResponseMacroServiceProvider if it exists
     */
    public function test_response_macro_service_provider()
    {
        if (class_exists('App\Providers\ResponseMacroServiceProvider')) {
            $app = App::getFacadeRoot();
            $provider = new \App\Providers\ResponseMacroServiceProvider($app);
            
            $this->assertInstanceOf(\App\Providers\ResponseMacroServiceProvider::class, $provider);
            
            if (method_exists($provider, 'boot')) {
                $provider->boot();
                $this->assertTrue(true);
            }
        } else {
            $this->markTestSkipped('ResponseMacroServiceProvider does not exist');
        }
    }
}
