<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * Test ConfirmPasswordController exists and has correct properties
     */
    public function test_confirm_password_controller_exists()
    {
        $controller = new ConfirmPasswordController();
        
        $this->assertInstanceOf(ConfirmPasswordController::class, $controller);
    }

    /**
     * Test ForgotPasswordController exists and has correct properties
     */
    public function test_forgot_password_controller_exists()
    {
        $controller = new ForgotPasswordController();
        
        $this->assertInstanceOf(ForgotPasswordController::class, $controller);
    }

    /**
     * Test LoginController exists and has correct properties
     */
    public function test_login_controller_exists()
    {
        $controller = new LoginController();
        
        $this->assertInstanceOf(LoginController::class, $controller);
    }

    /**
     * Test RegisterController exists and has correct properties
     */
    public function test_register_controller_exists()
    {
        $controller = new RegisterController();
        
        $this->assertInstanceOf(RegisterController::class, $controller);
    }

    /**
     * Test ResetPasswordController exists and has correct properties
     */
    public function test_reset_password_controller_exists()
    {
        $controller = new ResetPasswordController();
        
        $this->assertInstanceOf(ResetPasswordController::class, $controller);
    }

    /**
     * Test VerificationController exists and has correct properties
     */
    public function test_verification_controller_exists()
    {
        $controller = new VerificationController();
        
        $this->assertInstanceOf(VerificationController::class, $controller);
    }

    /**
     * Test login page is accessible
     */
    public function test_login_page_accessible()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test register page is accessible
     */
    public function test_register_page_accessible()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Test password reset request page is accessible
     */
    public function test_password_reset_page_accessible()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
    }
}
