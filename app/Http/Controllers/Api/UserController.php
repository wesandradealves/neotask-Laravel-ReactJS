<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8', 
        ]);

        $user = auth()->user();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Senha alterada com sucesso']);
    }
}
