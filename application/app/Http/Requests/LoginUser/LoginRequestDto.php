<?php

namespace App\Http\Requests\LoginUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LoginRequestDto",
 *     type="object",
 *     required ={"email","password"})
 *     {
 *        @OA\Property(property="email", type="string", description="User email"),
 *        @OA\Property(property="password", type="string", description="User password"),
 *    }
 * )
 */
class LoginRequestDto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

}
