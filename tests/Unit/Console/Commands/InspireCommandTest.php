<?php

namespace Tests\Unit\Console\Commands;

use Tests\TestCase;
use App\Console\Commands\InspireCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

class InspireCommandTest extends TestCase
{
    /**
     * Test InspireCommand instantiation
     */
    public function test_inspire_command_instantiation()
    {
        $command = new InspireCommand();
        
        $this->assertInstanceOf(InspireCommand::class, $command);
        $this->assertInstanceOf(\Illuminate\Console\Command::class, $command);
    }

    /**
     * Test InspireCommand properties
     */
    public function test_inspire_command_properties()
    {
        $command = new InspireCommand();
        
        $reflection = new \ReflectionClass($command);
        
        // Test signature property
        $signatureProperty = $reflection->getProperty('signature');
        $signatureProperty->setAccessible(true);
        $this->assertEquals('inspire', $signatureProperty->getValue($command));
        
        // Test description property
        $descriptionProperty = $reflection->getProperty('description');
        $descriptionProperty->setAccessible(true);
        $this->assertEquals('Display an inspiring quote', $descriptionProperty->getValue($command));
    }

    /**
     * Test InspireCommand handle method exists
     */
    public function test_inspire_command_handle_method()
    {
        $command = new InspireCommand();
        
        $this->assertTrue(method_exists($command, 'handle'));
        
        $reflection = new \ReflectionMethod($command, 'handle');
        $this->assertTrue($reflection->isPublic());
    }

    /**
     * Test InspireCommand namespace and structure
     */
    public function test_inspire_command_structure()
    {
        $command = new InspireCommand();
        $reflection = new \ReflectionClass($command);
        
        $this->assertEquals('App\Console\Commands', $reflection->getNamespaceName());
        $this->assertEquals('InspireCommand', $reflection->getShortName());
        
        // Test inheritance
        $parentClass = $reflection->getParentClass();
        $this->assertEquals('Illuminate\Console\Command', $parentClass->getName());
    }

    /**
     * Test command execution through Artisan facade
     */
    public function test_inspire_command_execution()
    {
        // Test that the command can be called
        $result = Artisan::call('inspire');
        
        // Should return 0 for success
        $this->assertEquals(0, $result);
        
        // Should have output
        $output = Artisan::output();
        $this->assertNotEmpty($output);
        $this->assertIsString($output);
    }
}
