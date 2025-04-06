<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        try {
            // Aqui você pode colocar debug temporário:
            \Log::info('🔐 Entrou no changePassword');
            
            // E ver se o usuário está autenticado:
            \Log::info('Usuário autenticado:', ['user' => Auth::user()]);

            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6|confirmed',
            ]);
    
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }
    
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 400);
            }
    
            $user->password = Hash::make($request->new_password);
            $user->save();
    
            return response()->json(['message' => 'Password changed successfully.']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
