<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\ProductController;
use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\OrderController;
use App\Http\Controllers\API\V1\InvoiceController;
use App\Http\Controllers\API\V1\ShipmentController;
use App\Http\Controllers\API\V1\GitHubController;
use App\Http\Controllers\API\V1\ReviewController;
use App\Http\Controllers\API\V1\CouponCodeController;
use App\Http\Controllers\API\V1\WishlistController;
use App\Http\Controllers\API\AuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/address', [AuthController::class, 'address']);
    Route::get('/address/{id}', [AuthController::class, 'getAddress']);
    Route::put('/address/{id}', [AuthController::class, 'updateAddress']);
    Route::get('/orders/{id}', [AuthController::class, 'getOrders']);
});

Route::middleware('api')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('shipments', ShipmentController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('coupons', CouponCodeController::class);
    Route::resource('wishlists', WishlistController::class);
    Route::get('/product/reviews/{id}', [ReviewController::class, 'reviewsByProduct']);
    // Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/github/token', [GitHubController::class, 'getToken']);
});

// Route::post('/create', [ProductController::class, 'create']);
// Route::get('/get', [ProductController::class, 'get']);
// Route::patch('/edit/{id}', [ProductController::class, 'edit']);
// Route::post('/update/{id}', [ProductController::class, 'update']);
// Route::delete('/delete/{id}', [ProductController::class, 'delete']);