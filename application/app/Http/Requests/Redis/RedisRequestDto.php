<?php

namespace App\Http\Requests\Redis;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="RedisRequestDto",
 *     type="object",
 *     required ={"name","email"})
 *     {
 *        @OA\Property(property="name", type="string", description="User name"),
 *        @OA\Property(property="email", type="string", description="User email"),
 *    }
 * )
 */
class RedisRequestDto extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules ()
    {
        return [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo name é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
        ];
    }
}
