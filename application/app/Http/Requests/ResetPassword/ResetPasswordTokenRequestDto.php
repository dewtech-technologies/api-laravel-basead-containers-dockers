<?php

namespace App\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ResetPasswordTokenRequestDto",
 *     type="object",
 *     required={"email", "token", "password"},
 *     @OA\Property(property="email", type="string", format="email", description="Endereço de email do usuário"),
 *     @OA\Property(property="token", type="string", description="Token de redefinição de senha"),
 *     @OA\Property(property="password", type="string", description="Nova senha do usuário"),
 * )
 */
class ResetPasswordTokenRequestDto extends FormRequest
{
    public function authorize()
    {
        // Determine se o usuário está autorizado a fazer este request.
        // Retorne true se o usuário estiver autorizado.
        return true;
    }

    public function rules()
    {
        // Aqui você pode definir as regras de validação para o request.
        return [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ];
    }
}


