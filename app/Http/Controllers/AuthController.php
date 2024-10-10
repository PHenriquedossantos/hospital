<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // validação do request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // tentativa de login
        $email = $request->email;
        $password = $request->password;
        $attempt = auth()->attempt([
            'email' => $email,
            'password' => $password
        ]);

        if(!$attempt)
        {
            return ApiResponse::unauthorized();
        }

        // autentica o usuário
        $user = auth()->user();
        // $token = $user->createToken($user->name)->plainTextToken;
        $token = $user->createToken($user->name, ['*'], now()->addHour())->plainTextToken;
        // return o token de acesso para requisições da API
        return ApiResponse::success(
            [
                'user' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
            );
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::success('Logout with success');
    }
}
