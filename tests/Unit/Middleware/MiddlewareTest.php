<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SecureHeader;
use Mockery;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustHosts;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    /**
     * Test Authenticate middleware exists
     */
    public function test_authenticate_middleware_exists()
    {
        $auth = Mockery::mock(\Illuminate\Contracts\Auth\Factory::class);
        $middleware = new Authenticate($auth);
        
        $this->assertInstanceOf(Authenticate::class, $middleware);
    }

    /**
     * Test ContentSecurityPolicy middleware exists
     */
    public function test_content_security_policy_middleware_exists()
    {
        $middleware = new ContentSecurityPolicy();
        
        $this->assertInstanceOf(ContentSecurityPolicy::class, $middleware);
        $this->assertTrue(method_exists($middleware, 'handle'));
    }

    /**
     * Test EncryptCookies middleware exists
     */
    public function test_encrypt_cookies_middleware_exists()
    {
        $middleware = new EncryptCookies(app('encrypter'));
        
        $this->assertInstanceOf(EncryptCookies::class, $middleware);
    }

    /**
     * Test PreventRequestsDuringMaintenance middleware exists
     */
    public function test_prevent_requests_during_maintenance_middleware_exists()
    {
        $app = Mockery::mock(\Illuminate\Contracts\Foundation\Application::class);
        $middleware = new PreventRequestsDuringMaintenance($app);
        
        $this->assertInstanceOf(PreventRequestsDuringMaintenance::class, $middleware);
    }

    /**
     * Test RedirectIfAuthenticated middleware exists
     */
    public function test_redirect_if_authenticated_middleware_exists()
    {
        $middleware = new RedirectIfAuthenticated();
        
        $this->assertInstanceOf(RedirectIfAuthenticated::class, $middleware);
        $this->assertTrue(method_exists($middleware, 'handle'));
    }

    /**
     * Test SecureHeader middleware exists
     */
    public function test_secure_header_middleware_exists()
    {
        $middleware = new SecureHeader();
        
        $this->assertInstanceOf(SecureHeader::class, $middleware);
        $this->assertTrue(method_exists($middleware, 'handle'));
    }

    /**
     * Test TrimStrings middleware exists
     */
    public function test_trim_strings_middleware_exists()
    {
        $middleware = new TrimStrings();
        
        $this->assertInstanceOf(TrimStrings::class, $middleware);
    }

    /**
     * Test TrustHosts middleware exists
     */
    public function test_trust_hosts_middleware_exists()
    {
        $middleware = new TrustHosts(app());
        
        $this->assertInstanceOf(TrustHosts::class, $middleware);
    }

    /**
     * Test TrustProxies middleware exists
     */
    public function test_trust_proxies_middleware_exists()
    {
        $middleware = new TrustProxies(app());
        
        $this->assertInstanceOf(TrustProxies::class, $middleware);
    }

    /**
     * Test VerifyCsrfToken middleware exists
     */
    public function test_verify_csrf_token_middleware_exists()
    {
        $middleware = new VerifyCsrfToken(app(), app('encrypter'));
        
        $this->assertInstanceOf(VerifyCsrfToken::class, $middleware);
    }
}
