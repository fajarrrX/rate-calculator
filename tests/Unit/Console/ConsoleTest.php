<?php

namespace Tests\Unit\Console;

use Tests\TestCase;
use Illuminate\Console\Kernel;

class ConsoleTest extends TestCase
{
    /**
     * Test console kernel exists
     */
    public function test_console_kernel_exists()
    {
        $this->assertTrue(class_exists(\App\Console\Kernel::class));
    }

    /**
     * Test console kernel commands registration
     */
    public function test_console_kernel_commands()
    {
        $kernel = new \App\Console\Kernel(app(), app('events'));
        
        $this->assertInstanceOf(\App\Console\Kernel::class, $kernel);
    }
}
