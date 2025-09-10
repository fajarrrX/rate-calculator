<?php

namespace Tests\Unit\Storage;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;

class StorageTest extends TestCase
{
    /**
     * Test storage disks configuration
     */
    public function test_storage_disks_configuration()
    {
        $this->assertTrue(Storage::disk('local')->exists('.'));
        $this->assertInstanceOf(\Illuminate\Contracts\Filesystem\Filesystem::class, Storage::disk('local'));
    }

    /**
     * Test public disk configuration if exists
     */
    public function test_public_disk_configuration()
    {
        if (config('filesystems.disks.public')) {
            $this->assertInstanceOf(\Illuminate\Contracts\Filesystem\Filesystem::class, Storage::disk('public'));
        }
        
        $this->assertTrue(true); // Always pass if public disk not configured
    }

    /**
     * Test storage facade
     */
    public function test_storage_facade()
    {
        $this->assertTrue(class_exists(\Illuminate\Support\Facades\Storage::class));
        $this->assertInstanceOf(\Illuminate\Filesystem\FilesystemManager::class, Storage::getFacadeRoot());
    }
}
