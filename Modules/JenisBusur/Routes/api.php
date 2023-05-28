<?php

use Illuminate\Http\Request;
use Modules\JenisBusur\Http\Controllers\JenisBusurController;

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

Route::middleware('auth:sanctum','verified')->group(function(){
    Route::apiResource('jenisbusur', JenisBusurController::class);
});