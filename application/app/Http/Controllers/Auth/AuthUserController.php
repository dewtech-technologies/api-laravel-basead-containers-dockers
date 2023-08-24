<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser\LoginRequestDto;
use App\Http\Requests\LoginUser\RefreshTokenRequestDto;

class AuthUserController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Autenticação de usuário",
     *     description="Login para obter o token de acesso",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", description="User email"),
     *                 @OA\Property(property="password", type="string", description="User password"),
     *                 example={"email": "user@example.com", "password": "password1234"}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Autenticado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", example="abcd1234"),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="expires_at", type="string", example="2023-08-12 12:34:56")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *     ),
     * )
     */
    public function login(LoginRequestDto $request)
    {
        return $this->authService->authenticate($request);
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     summary="Invalidar token de acesso",
     *     description="Efetuar logout e invalida o token de acesso",
     *     security={{"jwtAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function logout()
    {
        return $this->authService->logout();
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/refreshtoken",
     *     operationId="refreshtoken",
     *     tags={"Auth"},
     *     summary="Refresh token",
     *     description="Renova token de acesso",
     *     security={{"jwtAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="token", type="string", description="Bearer Token"),
     *                 example={"token": "Bearer abcd1234"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Update token com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *         )
     *     )
     * )
     */
    public function refreshToken(RefreshTokenRequestDto $request)
    {
        return $this->authService->refreshAccessToken($request);
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/validateToken",
     *     operationId="validateToken",
     *     tags={"Auth"},
     *     summary="validate Token",
     *     description="Valida token de acesso",
     *     security={{"jwtAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Valida token com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *         )
     *     )
     * )
     */
    public function validateToken()
    {
        return $this->authService->validateToken();
    }


}
