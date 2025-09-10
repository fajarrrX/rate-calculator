<?php

use App\Http\Controllers\API\RatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function () {
    return response()->json(['message' => 'Rates Calculator API', 'status' => 'Connected']);;
});

Route::get('test-db', [RatesController::class, 'testDb']);
Route::get('sender', [RatesController::class, 'sender']);
Route::get('receiver', [RatesController::class, 'receiver']);
Route::get('package-type', [RatesController::class, 'packageType']);
Route::post('calculate', [RatesController::class, 'calculate']);