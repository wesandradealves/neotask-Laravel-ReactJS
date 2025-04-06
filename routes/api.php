<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SongController;
use App\Http\Controllers\Api\SuggestionController;

Route::get('/health-test', function () {
    return ['status' => 'OK'];
});

Route::middleware('api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/suggestions', [SuggestionController::class, 'index']);
        Route::post('/suggestions', [SuggestionController::class, 'store']);
    
        Route::middleware('is_admin')->group(function () {
            // 👇 apenas admins podem editar ou deletar sugestões
            Route::patch('/suggestions/{suggestion}/approve', [SuggestionController::class, 'approve']);
            Route::patch('/suggestions/{suggestion}/reject', [SuggestionController::class, 'reject']);
            Route::patch('/suggestions/{suggestion}', [SuggestionController::class, 'update']);
            Route::delete('/suggestions/{suggestion}', [SuggestionController::class, 'destroy']);
    
            // ✅ apenas admins podem manipular músicas
            Route::apiResource('songs', SongController::class)->only(['store', 'update', 'destroy']);
        });
    
        // ✅ qualquer usuário autenticado pode acessar seus dados
        Route::get('/user', fn(Request $request) => $request->user());
    });
    
    // ✅ qualquer usuário (mesmo não autenticado) pode visualizar músicas
    Route::get('/songs', [SongController::class, 'index']);
    Route::get('/songs/top', [SongController::class, 'topPlayed']);
});
