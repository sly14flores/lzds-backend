<?php

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

use App\Http\Controllers\Api\AddressesController;
use App\Http\Controllers\Api\SelectionsController;

/**
 * Addresses
 */
Route::prefix('address')->group(function() {

    Route::get('regions', [AddressesController::class, 'regions']);
    Route::get('provinces/{code}', [AddressesController::class, 'provinces']);
    Route::get('cities/{code}', [AddressesController::class, 'cities']);
    Route::get('barangays/{code}', [AddressesController::class, 'barangays']);

});

/**
 * Selections
 */
Route::prefix('selections')->group(function() {

    Route::get('dialects', [SelectionsController::class, 'dialects']);  

});


