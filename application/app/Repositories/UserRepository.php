<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    /**
     * @var User
     */
    protected $users;

    public function __construct(User $model)
    {
        $this->users = new User();
    }

    public function getAll()
    {
        return $this->users->all();
    }

    public function getById($id)
    {
        $model = $this->users->find($id);

        if (!$model) {
            // Se o modelo não foi encontrado, retornamos uma resposta com status 404 (não encontrado).
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        return $model;
    }
    public function create($request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->remember_token =Hash::make($request->remember_token);
        $user->save();
        return $user;
    }
    public function update($id, $request)
    {
        $model = $this->users->find($id);

        if(!$model){
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }

        $existingUser = $this->users->where('email', $request->email)->first();
        if($existingUser && $existingUser->id !== $id){
            return response()->json(['message' => 'Email já cadastrado'], 400);
        }

        $changed = false;

        if ($model->name !== $request->name) {
            $model->name = $request->name;
            $changed = true;
        }
        if ($request->password) {
            $model->password = Hash::make($request->password);
            $changed = true;
        }
        if ($model->remember_token !== $request->remember_token) {
            $model->remember_token = Hash::make($request->remember_token);
            $changed = true;
        }

        if ($changed) {
            $model->updated_at = date(self::DATE_FORMAT);
            $model->update();
        }
        return $model;

    }
    public function delete($id)
    {
        return $this->users->find($id)->delete();
    }

}
