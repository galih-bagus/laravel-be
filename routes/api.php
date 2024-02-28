<?php

use App\Http\Controllers\API\v1\AuthController;
use App\Http\Controllers\API\v1\ProductTypeController;
use App\Http\Controllers\API\v1\ProductMainCategoryController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/test', function (Request $request) {
            return $request->user();
        });
        Route::prefix('master-data')->group(function () {
            Route::resource('product-type', ProductTypeController::class);
            Route::resource('product-main-category', ProductMainCategoryController::class);
        });
    });
});
