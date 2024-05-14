<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//TODO add restrictions as middleware for admins

Route::get('products', [ProductController::class, 'all']);
Route::get('product/{id}', [ProductController::class, 'find']);
Route::post('product', [ProductController::class, 'add']);
Route::patch('product/{id}', [ProductController::class, 'update']);

Route::get('order/{id}', [OrderController::class, 'find']);
Route::get('orders', [OrderController::class, 'allForUser']);
Route::post('order', [OrderController::class, 'add']);

Route::get('order/{order_id}/order-details', [OrderDetailController::class, 'allOfOrder']);
Route::post('order-detail/create', [OrderDetailController::class, 'add']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

});
