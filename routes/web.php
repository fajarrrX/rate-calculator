<?php

use App\Http\Controllers\CountryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RateController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::any('/calculator/{all}', function ($all) {
    return File::get(public_path() . '/calculator/index.html');
});

Route::any('/app/{all}', function ($all) {
    return File::get(public_path() . '/app/index.html');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('country/{country}/rates', [CountryController::class, 'rates'])->name('country.rates');
    Route::get('country/{country}/receivers', [CountryController::class, 'receivers'])->name('country.receivers');
    Route::resource('country', CountryController::class);
    
    Route::post('/rate/upload', [RateController::class, 'upload'])->name('rate.upload');
    Route::post('/rate/download', [RateController::class, 'download'])->name('rate.download');
});