<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestsDto extends FormRequest
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
            'password' => 'required|min:6',
            'remember' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo name é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email informado é inválido.',
            'password.required' => 'O campo password é obrigatório.',
            'password.min' => 'A senha deve conter pelo menos 6 caracteres.',
            'remember.required' => 'O campo remember é obrigatório.',
            'remember.boolean' => 'O campo remember deve ser verdadeiro ou falso.',
        ];
    }
}
