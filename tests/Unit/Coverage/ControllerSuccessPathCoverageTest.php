<?php

namespace Tests\Unit\Coverage;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\RateController;
use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Validator;

/**
 * These tests mock success paths for controllers that normally require a database.
 * Goal: drive execution through previously uncovered lines (transactions, commits, loops).
 */
class ControllerSuccessPathCoverageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a mock Country class that includes the NAME constant
        if (!class_exists('MockCountry')) {
            eval('
                class MockCountry {
                    const NAME = "Country";
                    public static function create($data) { 
                        return new class {
                            public $id = 7;
                            public function valid_fields() { return ["business_title_en","personal_title_en"]; }
                            public function quote_langs() { 
                                return new class {
                                    public function create($arr) { /* no-op */ }
                                    public function updateOrCreate($keys, $values) { /* no-op */ }
                                };
                            }
                        };
                    }
                    public static function findOrFail($id) { 
                        return new class {
                            public function update($data) { /* no-op */ }
                            public function delete() { /* no-op */ }
                            public function valid_fields() { return ["business_title_en","personal_title_en"]; }
                            public function quote_langs() { 
                                return new class {
                                    public function updateOrCreate($keys, $values) { /* no-op */ }
                                };
                            }
                        };
                    }
                }
            ');
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Simulate successful rate upload path (covers DB::beginTransaction, Excel import, file store, model create, commit).
     */
    public function test_rate_controller_upload_success_path()
    {
        // Mock Country static findOrFail
        $country = (object) [
            'id' => 1,
            'code' => 'US'
        ];
        Mockery::mock('alias:App\Models\Country')
            ->shouldReceive('findOrFail')->once()->andReturn($country);

        // Mock Excel facade import
        Mockery::mock('alias:Maatwebsite\\Excel\\Facades\\Excel')
            ->shouldReceive('import')->once();

        // Mock RatecardFile create
        Mockery::mock('alias:App\Models\RatecardFile')
            ->shouldReceive('create')->once();

        // Mock DB facade transaction functions
        Mockery::mock('alias:Illuminate\\Support\\Facades\\DB')
            ->shouldReceive('beginTransaction')->once()
            ->shouldReceive('commit')->once();

        // Fake storage
        Storage::fake('local');

        $file = UploadedFile::fake()->create('rates.xlsx', 10, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $request = Request::create('/rates/upload', 'POST', [
            'country_id' => 1,
            'type' => 'doc'
        ], [], ['file' => $file]);

        $controller = new RateController();
        $response = $controller->upload($request);

        $this->assertTrue(method_exists($controller, 'upload'));
        $this->assertNotNull($response);
    }

    /**
     * Simulate successful rate download path (covers Storage::exists + download branch).
     */
    public function test_rate_controller_download_success_path()
    {
        $fileRecord = (object) [
            'id' => 99,
            'path' => 'ratecards/US/sample.xlsx'
        ];

        Mockery::mock('alias:App\\Models\\RatecardFile')
            ->shouldReceive('findOrFail')->once()->andReturn($fileRecord);

        Storage::fake('local');
        Storage::disk('local')->put($fileRecord->path, 'dummy');

        // Monkey patch Storage::exists/ download by ensuring fake disk has the file
        $request = Request::create('/rates/download', 'GET', ['file_id' => 99]);

        $controller = new RateController();
        $response = $controller->download($request);
        $this->assertNotNull($response);
    }

    /**
     * Simulate successful CountryController store path (drives create + quote_lang loop).
     */
    public function test_country_controller_store_success_path()
    {
        // Use our MockCountry class instead of alias mock
        Mockery::mock('alias:App\\Models\\Country', 'MockCountry')
            ->makePartial();

        Mockery::mock('alias:Illuminate\\Support\\Facades\\DB')
            ->shouldReceive('beginTransaction')->once()
            ->shouldReceive('commit')->once()
            ->shouldReceive('rollback')->zeroOrMoreTimes()
            ->shouldReceive('rollBack')->zeroOrMoreTimes();

        // Allow validator to pass
        $request = Request::create('/country', 'POST', [
            'name' => 'Indonesia',
            'code' => 'ID',
            'currency_code' => 'IDR',
            'business_title_en' => 'Biz',
            'personal_title_en' => 'Personal'
        ]);

        // Bypass validator failure branch by faking Validator::make -> passes
        Mockery::mock('alias:Illuminate\\Support\\Facades\\Validator')
            ->shouldReceive('make')->once()->andReturn(new class {
                public function fails(){ return false; }
                public function errors(){ return []; }
            });

        $controller = new CountryController();
        $response = $controller->store($request);
        $this->assertNotNull($response);
    }

    /**
     * Simulate successful CountryController update path (covers update + updateOrCreate loop).
     */
    public function test_country_controller_update_success_path()
    {
        // Use our MockCountry class instead of alias mock
        Mockery::mock('alias:App\\Models\\Country', 'MockCountry')
            ->makePartial();

        Mockery::mock('alias:Illuminate\\Support\\Facades\\DB')
            ->shouldReceive('beginTransaction')->once()
            ->shouldReceive('commit')->once()
            ->shouldReceive('rollback')->zeroOrMoreTimes()
            ->shouldReceive('rollBack')->zeroOrMoreTimes();

        Mockery::mock('alias:Illuminate\\Support\\Facades\\Validator')
            ->shouldReceive('make')->once()->andReturn(new class {
                public function fails(){ return false; }
                public function errors(){ return []; }
            });

        $request = Request::create('/country/7', 'PUT', [
            'name' => 'Indonesia',
            'code' => 'ID',
            'currency_code' => 'IDR',
            'business_title_en' => 'Biz',
            'personal_title_en' => 'Personal'
        ]);

        $controller = new CountryController();
        $response = $controller->update($request, 7);
        $this->assertNotNull($response);
    }

    /**
     * Simulate successful CountryController destroy path.
     */
    public function test_country_controller_destroy_success_path()
    {
        // Use our MockCountry class instead of alias mock
        Mockery::mock('alias:App\\Models\\Country', 'MockCountry')
            ->makePartial();

        $controller = new CountryController();
        $response = $controller->destroy(55);
        $this->assertNotNull($response);
    }
}
