<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:60,1')->group(function () {
Route::resource('products',ProductController::class)->except(['create','edit']);

Route::post('products/{id}/stock',[ProductController::class,'adjustStock']);
Route::get('products/low-stock',[ProductController::class,'listProductBelowThreshhold']);
});
