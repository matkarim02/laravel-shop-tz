<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('{order}', [OrderController::class, 'show']);
    Route::post('/', [OrderController::class, 'store']);
    Route::patch('{order}', [OrderController::class, 'update']);
    Route::delete('{order}', [OrderController::class, 'destroy']);
});


