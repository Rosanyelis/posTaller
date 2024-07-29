<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/get-stock/{product}', [App\Http\Controllers\Api\V1\ProductController::class, 'getStock'])->name('get-stock');
Route::get('/update-stock', [App\Http\Controllers\Api\V1\ProductController::class, 'updateStock'])->name('update-stock');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
