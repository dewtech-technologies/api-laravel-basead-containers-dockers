<?php

namespace App\Services;

use app\Http\Requests\UpdatePassword\UpdatePasswordRequestDto;
use app\Http\Requests\ResetPassword\ResetPasswordTokenRequestDto;
use App\Repositories\AuthRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Lcobucci\JWT\Token\Plain;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\RefreshTokenRepository;
use App\Http\Requests\LoginUser\LoginRequestDto;
use App\Http\Requests\LoginUser\RefreshTokenRequestDto;
use App\Http\Requests\LoginApplication\LoginApplicationRequestDto;
use App\Notifications\ResetPasswordNotification;

class AuthService
{
    protected $tokenRedisService;
    protected $authRepository;

    public function __construct(TokenRedisService $tokenRedisService, AuthRepository $authRepository)
    {
        $this->tokenRedisService = $tokenRedisService;
        $this->authRepository = $authRepository;
    }

    public function authenticate(LoginRequestDto $requestDto)
    {
        $credentials = [
            'email' => $requestDto->email,
            'password' => $requestDto->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('API Token');

            // Novo
            $decodedPayload = decode_jwt($tokenResult->accessToken);

            if ($decodedPayload && isset($decodedPayload->jti)) {
                $jti = $decodedPayload->jti;
                // Use $jti as the key in Redis or for other purposes
                $this->tokenRedisService->store($jti, $tokenResult->accessToken);
            }
            // Fim do novo

            // Gerar um refresh token manualmente
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            $attributes = [
                'id' => $tokenResult->token->id,
                'access_token_id' => $tokenResult->token->id,
                'revoked' => false,
                'expires_at' => now()->addDays(30)
            ];
            $refreshToken = $refreshTokenRepository->create($attributes); // Substitua o prazo de validade conforme necessário

            $expiration = now()->addSeconds(config('auth.expires_in'));

            return response()->json([
                'token_type' => 'Bearer',
                'expires_in' => $expiration->timestamp,
                'access_token' => $tokenResult->accessToken,
                'refresh_token' => $refreshToken->id, // Retorne o ID do refresh token
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function logout()
    {
        Auth::user()->tokens->each(function ($token) {
            $decodedPayload = decode_jwt($token);
            if ($decodedPayload && isset($decodedPayload->jti)) {
                $jti = $decodedPayload->jti;
                // Use $jti as the key in Redis or for other purposes
                $this->tokenRedisService->remove($jti);
            }
            $token->revoke();
        });
        return response()->json(['message' => 'Logout realizado com sucesso!']);
    }

    public function refreshAccessToken(RefreshTokenRequestDto $request)
    {
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshToken  = $refreshTokenRepository->find($request->refresh_token);

        if (!$refreshToken || $refreshToken->revoked) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        // Revoke the current refresh token
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($refreshToken->access_token_id);

        // Create a new access token and refresh token
        $user = Auth::user();
        $newRefreshToken = $user->createToken('API Token Refresh');

        // Novo
        $decodedPayload = decode_jwt($newRefreshToken->accessToken);

        if ($decodedPayload && isset($decodedPayload->jti)) {
            $jti = $decodedPayload->jti;
            // Use $jti as the key in Redis or for other purposes
            $this->tokenRedisService->store($jti, $newRefreshToken->accessToken);
        }

        $expiration = now()->addSeconds(config('auth.expires_in'));

        return response()->json([
            'token_type' => 'Bearer',
            'expires_in' => $expiration->timestamp,
            'access_token' => $newRefreshToken->accessToken,
            'refresh_token' => $newRefreshToken->token->id,
        ]);
    }

    public function validateToken()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Token válido',
                'code'=> 200
            ]);

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    public function updatePassword(UpdatePasswordRequestDto $request)
    {
        // 1. Verifique se a senha atual é válida
        $user = Auth::user();
        return $this->authRepository->updatePassword($user,$request);
    }

    public function handleResetPasswordRequest($email)
    {
        $user = $this->authRepository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'Email não encontrado'], 404);
        }

        // Gere um token (por simplicidade, vamos usar o Str::random)
        $token = Str::random(60);
        $hashedToken = Hash::make($token);

        // Salva o token na base de dados (supomos que você tem um campo 'password_reset_token' no modelo User)
        $this->authRepository->setResetToken($user, $hashedToken);

        $user->notify(new ResetPasswordNotification($hashedToken));

        return response()->json(['message' => 'E-mail de redefinição de senha enviado com sucesso!']);
    }
    public function resetByToken(ResetPasswordTokenRequestDto $request)
    {
        return $this->authRepository->resetPasswordByToken($request);
    }

}
