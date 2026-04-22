<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OfficerController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/officers', [OfficerController::class, 'index']);
Route::post('/officers/login', [OfficerController::class, 'login']);
Route::post('/officers/register', [OfficerController::class, 'register']);

Route::middleware(['web'])->group(function () {
    Route::delete('/officers/{id}', [OfficerController::class, 'destroy']);
    Route::put('/officers/{id}/password', [OfficerController::class, 'updatePassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
});