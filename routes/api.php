<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//custom laravel throttle middleware
Route::middleware(['rate.limit'])->group(function () {
    Route::post('/registration', [RegistrationController::class, 'store']);    
});

//default laravel throttle middleware
Route::middleware(['throttle:customApi'])->group(function () {
    Route::get('/registration', [RegistrationController::class, 'index']);
});


