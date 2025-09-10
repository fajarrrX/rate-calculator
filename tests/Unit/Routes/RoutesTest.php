<?php

namespace Tests\Unit\Routes;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoutesTest extends TestCase
{
    /**
     * Test web routes are registered
     */
    public function test_web_routes_registered()
    {
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();
        })->filter()->toArray();

        // Test that some expected routes exist
        $this->assertTrue(Route::has('login'));
        $this->assertTrue(Route::has('register'));
        $this->assertTrue(Route::has('password.request'));
        
        // Just test that routes collection exists
        $this->assertGreaterThan(0, Route::getRoutes()->count());
    }

    /**
     * Test API routes are registered
     */
    public function test_api_routes_registered()
    {
        $apiRoutes = collect(Route::getRoutes())
            ->filter(function ($route) {
                return str_starts_with($route->uri(), 'api/');
            })
            ->count();

        $this->assertGreaterThan(0, $apiRoutes);
    }

    /**
     * Test route collections
     */
    public function test_route_collections()
    {
        $routes = Route::getRoutes();
        
        $this->assertInstanceOf(\Illuminate\Routing\RouteCollection::class, $routes);
        $this->assertGreaterThan(0, $routes->count());
    }
}
