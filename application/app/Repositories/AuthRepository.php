<?php

namespace App\Repositories;

use app\Http\Requests\UpdatePassword\UpdatePasswordRequestDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    public function updatePassword(UpdatePasswordRequestDto $request)
    {
        // 1. Verifique se a senha atual é válida
        $user = Auth::user();
        if(!Hash::check($request->current_password, $user->password)){
            return response()->json(['error'=> 'Você não pode utilizar uma senha já utilizada ou igual a atual'],401);
        }

        // 2. Atualize a senha
        $user->password = Hash::make($request->new_password);
        $user->save();

        // 3. Retorne a resposta (sucesso ou erro)
        return response()->json(['message'=>'Senha atualizada com sucesso']);
    }
}


