<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            return ApiResponse::unauthorized();
        }
    
        $user = auth()->user();
    
        $twoFactorCode = random_int(100000, 999999);
        $user->two_factor_code = $twoFactorCode;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();
    
        Mail::to($user->email)->send(new TwoFactorCodeMail($twoFactorCode));
    
        $token = $user->createToken($user->name, ['*'], now()->addHour())->plainTextToken;
    
        return ApiResponse::success([
            'message' => 'Código de verificação enviado. Verifique seu e-mail.',
            'user' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ]);
    }
    

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string',
        ]);
    
        $user = auth()->user();
    
        if (!$user) {
            return ApiResponse::unauthorized('Usuário não autenticado.');
        }
    
        if ($user->two_factor_code === $request->two_factor_code &&
            $user->two_factor_expires_at > now()) {
    
            $user->tokens()->delete();
    
            $token = $user->createToken($user->name, ['*'], now()->addHour())->plainTextToken;

            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();
    
            return ApiResponse::success([
                'message' => 'Login bem-sucedido!',
                'token' => $token,
            ]);
        }
    
        return ApiResponse::unauthorized('Código de verificação inválido ou expirado.');
    }
    

    private function isTwoFactorCodeValid($user, $twoFactorCode)
    {
        return $user->two_factor_code === $twoFactorCode && $user->two_factor_expires_at > now();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::success('Logout com sucesso');
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $token = $request->input('token');

        $tokenData = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if (!$tokenData) {
            return ApiResponse::error('Token inválido ou inexistente.', 404);
        }

        return ApiResponse::success('Token válido.');
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken($user->name, ['*'])->plainTextToken;

        return ApiResponse::success([
            'message' => 'Usuário registrado com sucesso!',
            'user' => $user->name,
            'email' => $user->email,
            'token' => $token,
        ]);
    }
}
