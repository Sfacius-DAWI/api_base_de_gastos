<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExpenseController;

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:api'])->group(function () {
    Route::get('expenses', [ExpenseController::class, 'index']);
    Route::post('expenses', [ExpenseController::class, 'store']);
    Route::get('expenses/{id}', [ExpenseController::class, 'show']);
    Route::put('expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('expenses/{id}', [ExpenseController::class, 'destroy']);
});