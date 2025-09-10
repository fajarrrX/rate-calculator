<?php

namespace Tests\Unit\Models;

use App\Models\RatecardFile;
use Tests\TestCase;

class RatecardFileTest extends TestCase
{
    /**
     * Test RatecardFile model can be instantiated
     */
    public function test_ratecard_file_instantiation()
    {
        $file = new RatecardFile();
        
        $this->assertInstanceOf(RatecardFile::class, $file);
    }
}
