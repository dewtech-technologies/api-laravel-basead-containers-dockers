<?php

namespace App\Http\Requests\UpdatePassword;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdatePasswordRequestDto",
 *     type="object",
 *     required={"current_password", "new_password"},
 *     @OA\Property(property="current_password", type="string", description="User's current password"),
 *     @OA\Property(property="new_password", type="string", description="User's new password"),
 * )
 */
class UpdatePasswordRequestDto extends FormRequest
{

    public function authorize():bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|different:current_password'
        ];
    }

}
