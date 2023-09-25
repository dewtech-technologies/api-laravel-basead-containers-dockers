<?php

namespace App\Http\Requests\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ResetPasswordRequestDto",
 *     type="object",
 *     required={"email"},
 *     @OA\Property(property="email", type="string", description="User's email address"),
 * )
 */
class ResetPasswordRequestDto extends FormRequest
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
            'email' => 'required|email|exists:users,email',
        ];
    }
    public function messages()
    {
        // Mensagens personalizadas de erro podem ser definidas aqui.
        return [
            'email.required' => 'O campo de e-mail é obrigatório.',
            'email.email'    => 'O e-mail fornecido não é válido.',
            'email.exists'   => 'Não foi encontrado nenhum usuário com este e-mail.',
        ];
    }
}

