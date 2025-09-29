<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;

// Public auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public book listing & show
Route::apiResource('books', BookController::class)->only(['index','show']);

// Protected book CRUD + logout
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // protect create/update/delete
    Route::apiResource('books', BookController::class)->only(['store','update','destroy']);
});
