<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\IsAdmin;

Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/change-password', [UserController::class, 'changePassword']);
        Route::get('/user', fn(Request $request) => $request->user());
        Route::post('/suggestions', [SuggestionController::class, 'store']);

        Route::middleware(IsAdmin::class)->group(function () {
            Route::get('/health-admin', fn() => ['message' => 'Você é admin']);
            Route::get('/suggestions', [SuggestionController::class, 'index']);
            Route::patch('/suggestions/{suggestion}/approve', [SuggestionController::class, 'approve']);
            Route::patch('/suggestions/{suggestion}/reject', [SuggestionController::class, 'reject']);
            Route::patch('/suggestions/{suggestion}', [SuggestionController::class, 'update']);
            Route::delete('/suggestions/{suggestion}', [SuggestionController::class, 'destroy']);
        
            Route::post('/songs', [SongController::class, 'store']);
            Route::put('/songs/{song}', [SongController::class, 'update']);
            Route::delete('/songs/{song}', [SongController::class, 'destroy']);
        });
    });

    Route::get('/songs', [SongController::class, 'index']);
    Route::get('/songs/top', [SongController::class, 'topPlayed']);
});
