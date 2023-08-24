<?php

namespace App\Http\Requests\LoginUser;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="RefreshTokenRequestDto",
 *     type="object",
 *     required ={"refresh_token"})
 *     {
 *        @OA\Property(property="token", type="string", description="Bearer token"),
 *    }
 * )
 */
class RefreshTokenRequestDto extends FormRequest
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
            'refresh_token' => 'required|string',
        ];
    }

}
