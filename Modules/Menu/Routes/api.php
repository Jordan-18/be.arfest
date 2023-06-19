<?php

use Illuminate\Http\Request;
use Modules\Menu\Http\Controllers\MenuAccessController;
use Modules\Menu\Http\Controllers\MenuController;

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
    Route::apiResource('menu', MenuController::class);
    Route::apiResource('menuaccess', MenuAccessController::class);

    Route::get('/menus', [MenuController::class, 'menus']);
    Route::get('/roleaccess/{id?}', [MenuAccessController::class, 'roleAccess']);
    Route::get('/rolepureaccess/{id?}', [MenuAccessController::class, 'rolePureAccess']);
    Route::put('/roleaccess/{id?}', [MenuAccessController::class, 'updateRoleAccess']);
});