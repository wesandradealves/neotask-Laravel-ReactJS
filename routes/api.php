<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;

Route::get('/health-test', function (Request $request) {
    return ['status' => 'OK', 'ip' => $request->ip()];
});

// Route::post('/change-password', [UserController::class, 'changePassword']);

Route::middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->post('/change-password', [UserController::class, 'changePassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/suggestions', function (Request $request) {
            return app(SuggestionController::class)->store($request);
        });

        Route::middleware('is_admin')->group(function () {
            Route::patch('/suggestions/{suggestion}/approve', function (Request $request, $suggestion) {
                return app(SuggestionController::class)->approve($request, $suggestion);
            });

            Route::patch('/suggestions/{suggestion}/reject', function (Request $request, $suggestion) {
                return app(SuggestionController::class)->reject($request, $suggestion);
            });

            Route::patch('/suggestions/{suggestion}', function (Request $request, $suggestion) {
                return app(SuggestionController::class)->update($request, $suggestion);
            });

            Route::delete('/suggestions/{suggestion}', function (Request $request, $suggestion) {
                return app(SuggestionController::class)->destroy($request, $suggestion);
            });

            Route::post('/songs', function (Request $request) {
                return app(SongController::class)->store($request);
            });

            Route::put('/songs/{song}', function (Request $request, $song) {
                return app(SongController::class)->update($request, $song);
            });

            Route::delete('/songs/{song}', function (Request $request, $song) {
                return app(SongController::class)->destroy($request, $song);
            });
        });

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });

    Route::get('/suggestions', function (Request $request) {
        return app(SuggestionController::class)->index($request);
    });

    Route::get('/songs', function (Request $request) {
        return app(SongController::class)->index($request);
    });

    Route::get('/songs/top', function (Request $request) {
        return app(SongController::class)->topPlayed($request);
    });
});
