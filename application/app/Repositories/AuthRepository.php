<?php

namespace App\Repositories;

use app\Http\Requests\UpdatePassword\UpdatePasswordRequestDto;
use app\Http\Requests\ResetPassword\ResetPasswordTokenRequestDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
    public function resetPasswordByToken(ResetPasswordTokenRequestDto $request)
    {
        // Busque o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Lançar um erro se o usuário não for encontrado
            throw new \Exception('Usuário não encontrado.');
        }

        // Verifique se o token está correto
        if ($request->token !== $user->password_reset_token) {
            // Lançar um erro se o token estiver incorreto
            throw new \Exception('Token inválido.');
        }

        // Atualize a senha do usuário
        $user->password = Hash::make($request->password);
        $user->password_reset_token = null; // limpar o token de redefinição de senha
        $user->save();

        return response(['message' => 'Senha redefinida com sucesso.']);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function setResetToken($user, $token)
    {
        $user->password_reset_token = $token;
        $user->save();
    }
}


