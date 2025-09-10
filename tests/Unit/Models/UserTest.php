<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * Test User model can be instantiated
     */
    public function test_user_instantiation()
    {
        $user = new User();
        
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * Test User model has expected fillable attributes
     */
    public function test_user_fillable_attributes()
    {
        $user = new User();
        
        $fillable = $user->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('password', $fillable);
    }

    /**
     * Test User model has expected hidden attributes
     */
    public function test_user_hidden_attributes()
    {
        $user = new User();
        
        $hidden = $user->getHidden();
        
        $this->assertContains('password', $hidden);
        $this->assertContains('remember_token', $hidden);
    }
}
