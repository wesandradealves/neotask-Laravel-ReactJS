<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Usuário não autenticado.'
            ], 401); // Unauthorized
        }
        
        if (!auth()->user()->is_admin) {
            return response()->json([
                'message' => 'Acesso restrito a administradores.'
            ], 403); // Forbidden
        }
        

        return $next($request);
    }
}
