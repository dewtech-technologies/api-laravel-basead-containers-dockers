<?php

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUser\LoginRequestDto;
use App\Http\Requests\LoginUser\RefreshTokenRequestDto;
use App\Http\Requests\UpdatePassword\UpdatePasswordRequestDto;
use App\Http\Requests\ResetPassword\ResetPasswordRequestDto;
use App\Http\Requests\ResetPassword\ResetPasswordTokenRequestDto;

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

    /**
     * @OA\Post(
     *     path="/v1/dewtech/update-password",
     *     operationId="updatePassword",
     *     tags={"Auth"},
     *     summary="Atualizar senha",
     *     description="Atualizar a senha do usuário logado",
     *     security={{"jwtAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="current_password", type="string", description="Current password"),
     *                 @OA\Property(property="new_password", type="string", description="New password"),
     *                 example={"current_password": "oldPass123", "new_password": "newPass1234"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function updatePassword(UpdatePasswordRequestDto $request)
    {
        return $this->authService->updatePassword($request);
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/reset-password",
     *     operationId="resetPasswordRequest",
     *     tags={"Auth"},
     *     summary="Solicita redefinição de senha",
     *     description="Envia um e-mail com um link para redefinir a senha",
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", description="User email"),
     *                 example={"email": "user@example.com"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email enviado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email não encontrado"
     *     )
     * )
     */
    public function resetPassword(ResetPasswordRequestDto $request)
    {
        return $this->authService->handleResetPasswordRequest($request->email);
    }

    /**
     * @OA\Post(
     *     path="/v1/dewtech/reset-password-token",
     *     tags={"Auth"},
     *     summary="Reset user password with a token",
     *     description="This endpoint resets the user's password using a password reset token.",
     *
     *     @OA\RequestBody(
     *         description="Reset Password Token Information",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ResetPasswordTokenRequestDto")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successful",
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, invalid input data"
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     * )
     */
    public function resetPasswordToken(ResetPasswordTokenRequestDto $request)
    {
        return $this->authService->resetByToken($request);
    }

}
