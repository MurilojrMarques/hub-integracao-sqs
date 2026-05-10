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
