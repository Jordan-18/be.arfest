<?php

use Illuminate\Http\Request;
use Modules\Event\Http\Controllers\EventController;
use Modules\Event\Http\Controllers\RelationEventController;

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
    Route::apiResource('event', EventController::class);
    Route::apiResource('relationevent', RelationEventController::class);

    Route::post('/updateimg/{id?}', [EventController::class, 'updateImg']);
});