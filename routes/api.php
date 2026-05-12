<?php

use App\Http\Controllers\Api\ProductUpdateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products/{sku}')->group(function () {
    Route::patch('/price', [ProductUpdateController::class, 'updatePrice']);
    Route::patch('/stock', [ProductUpdateController::class, 'updateStock']);
    Route::patch('/description', [ProductUpdateController::class, 'updateDescription']);
    Route::patch('/images', [ProductUpdateController::class, 'updateImages']);
    Route::patch('/tags', [ProductUpdateController::class, 'updateTags']);
});

Route::middleware('throttle:600,1')->group(function(){
    Route::patch('/products/{sku}/price', [ProductUpdateController::class, 'updatePrice']);
    Route::patch('/products/{sku}/stock', [ProductUpdateController::class, 'updateStock']);
    Route::patch('/products/{sku}/description', [ProductUpdateController::class, 'updateDescription']);
    Route::patch('/products/{sku}/images', [ProductUpdateController::class, 'updateImages']);
    Route::patch('/products/{sku}/tags', [ProductUpdateController::class, 'updateTags']);
});
