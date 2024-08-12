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

Route::get('{language}/products', [ProductController::class, 'all']);
Route::get('{language}/product/{id}', [ProductController::class, 'find']);
Route::post('product', [ProductController::class, 'add']);
Route::patch('product/{id}', [ProductController::class, 'update']);
Route::delete('product/{id}', [ProductController::class, 'delete']);

Route::get('order/{id}', [OrderController::class, 'find']);
Route::get('orders', [OrderController::class, 'allForUser']);
Route::post('order', [OrderController::class, 'add']);

Route::get('order/{order_id}/order-details', [OrderDetailController::class, 'allOfOrder']);
Route::post('order-detail/create', [OrderDetailController::class, 'add']);

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});
