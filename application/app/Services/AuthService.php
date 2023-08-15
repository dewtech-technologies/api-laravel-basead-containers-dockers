<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\LoginUser\LoginRequestDto;
use App\Http\Requests\LoginApplication\LoginApplicationRequestDto;

class AuthService
{
    public function authenticate(LoginRequestDto $requestDto)
    {
        $credentials = [
            'email' => $requestDto->email,
            'password' => $requestDto->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->accessToken;
            $expiration = now()->addSeconds(config('auth.expires_in')); // Define a expiração do token

            return response()->json([
                'token_type' => 'Bearer',
                'expires_in' => $expiration->timestamp,
                'access_token' => $token,
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function authenticateApp(LoginApplicationRequestDto $request)
    {
        $response = Http::post(config('app.url') . '/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
            'scope' => '',
        ]);

        return $response->json();
    }
}
