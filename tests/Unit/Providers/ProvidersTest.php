<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\BroadcastServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\ResponseMacroServiceProvider;
use App\Providers\RouteServiceProvider;
use Tests\TestCase;

class ProvidersTest extends TestCase
{
    /**
     * Test AppServiceProvider can be instantiated
     */
    public function test_app_service_provider_exists()
    {
        $provider = new AppServiceProvider(app());
        
        $this->assertInstanceOf(AppServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'register'));
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test AuthServiceProvider can be instantiated
     */
    public function test_auth_service_provider_exists()
    {
        $provider = new AuthServiceProvider(app());
        
        $this->assertInstanceOf(AuthServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test BroadcastServiceProvider can be instantiated
     */
    public function test_broadcast_service_provider_exists()
    {
        $provider = new BroadcastServiceProvider(app());
        
        $this->assertInstanceOf(BroadcastServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test EventServiceProvider can be instantiated
     */
    public function test_event_service_provider_exists()
    {
        $provider = new EventServiceProvider(app());
        
        $this->assertInstanceOf(EventServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test ResponseMacroServiceProvider can be instantiated
     */
    public function test_response_macro_service_provider_exists()
    {
        $provider = new ResponseMacroServiceProvider(app());
        
        $this->assertInstanceOf(ResponseMacroServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'register'));
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test RouteServiceProvider can be instantiated
     */
    public function test_route_service_provider_exists()
    {
        $provider = new RouteServiceProvider(app());
        
        $this->assertInstanceOf(RouteServiceProvider::class, $provider);
        $this->assertTrue(method_exists($provider, 'boot'));
    }

    /**
     * Test response macros are registered
     */
    public function test_response_macros_registered()
    {
        // Boot the ResponseMacroServiceProvider
        $provider = new ResponseMacroServiceProvider(app());
        $provider->boot();

        // Test that the macros exist
        $this->assertTrue(\Illuminate\Support\Facades\Response::hasMacro('success'));
        $this->assertTrue(\Illuminate\Support\Facades\Response::hasMacro('validationError'));
    }
}
