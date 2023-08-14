<?php

namespace App\Http\Requests\LoginApplication;

use OpenApi\Annotations as OA;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="LoginApplicationRequestDto",
 *     type="object",
 *     required={"client_id","secret"},
 *     @OA\Property(property="client_id", type="int64", description="Id da aplicação cliente"),
 *     @OA\Property(property="secret", type="string", description="Secret da aplicação cliente")
 * )
 */
class LoginApplicationRequestDto extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required',
            'secret' => 'required',
        ];
    }
}
