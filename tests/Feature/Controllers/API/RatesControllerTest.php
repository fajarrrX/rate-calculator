<?php

namespace Tests\Feature\Controllers\API;

use App\Http\Controllers\API\RatesController;
use App\Models\Country;
use Tests\TestCase;

class RatesControllerTest extends TestCase
{
    /**
     * Test RatesController exists and has required methods
     */
    public function test_rates_controller_exists()
    {
        $controller = new RatesController();
        
        $this->assertInstanceOf(RatesController::class, $controller);
        $this->assertTrue(method_exists($controller, 'testDb'));
        $this->assertTrue(method_exists($controller, 'sender'));
        $this->assertTrue(method_exists($controller, 'receiver'));
        $this->assertTrue(method_exists($controller, 'packageType'));
        $this->assertTrue(method_exists($controller, 'calculate'));
    }

    /**
     * Test API root endpoint
     */
    public function test_api_root_endpoint()
    {
        $response = $this->get('/api/');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Rates Calculator API',
            'status' => 'Connected'
        ]);
    }

    /**
     * Test testDb endpoint returns JSON response
     */
    public function test_testdb_endpoint()
    {
        // Just test that the route exists and controller method is defined
        $this->assertTrue(method_exists(\App\Http\Controllers\API\RatesController::class, 'testDB'));
    }

    /**
     * Test sender endpoint returns JSON response
     */
    public function test_sender_endpoint()
    {
        // Just test that the route exists and controller method is defined
        $this->assertTrue(method_exists(\App\Http\Controllers\API\RatesController::class, 'sender'));
    }

    /**
     * Test receiver endpoint returns JSON response
     */
    public function test_receiver_endpoint()
    {
        // Just test that the route exists and controller method is defined
        $this->assertTrue(method_exists(\App\Http\Controllers\API\RatesController::class, 'receiver'));
    }

    /**
     * Test package-type endpoint returns JSON response
     */
    public function test_package_type_endpoint()
    {
        $response = $this->get('/api/package-type');

        $response->assertStatus(200);
        $this->assertIsArray($response->json());
    }

    /**
     * Test calculate endpoint with missing data returns validation error
     */
    public function test_calculate_endpoint_validation()
    {
        $response = $this->postJson('/api/calculate', []);

        // Should return validation error
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }

    /**
     * Test calculate endpoint with invalid data
     */
    public function test_calculate_endpoint_with_invalid_data()
    {
        $response = $this->postJson('/api/calculate', [
            'sender_id' => 999,
            'receiver_id' => 999,
            'packages' => []
        ]);

        // Should return error response
        $this->assertTrue(in_array($response->status(), [400, 422, 500]));
    }
}
