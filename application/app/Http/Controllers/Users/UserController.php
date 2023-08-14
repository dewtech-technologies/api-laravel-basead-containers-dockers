<?php

namespace App\Http\Controllers\Users;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequestsDto;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }
     /**
     * @OA\Get(
     *      tags={"Users"},
     *      path="/v1/dewtech/users",
     *      summary="Lista usuários",
     *      description="Retorna uma lista de usuários.",
     *      @OA\Response(
     *          response=200,
     *          description="Operação realizada com sucesso",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UserRequestsDto")
     *          )
     *      )
     * )
     */
    public function index()
    {
        return $this->userService->getAll();
    }


    /**
     * @OA\Post(
     *      tags={"Users"},
     *      path="/v1/dewtech/users",
     *      summary="Cadastra usuário",
     *      description="Cadastramento de usuário.",
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="remember_token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Usuário criado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *      )
     * )
     */
    public function store(UserRequestsDto $request)
    {
        return $this->userService->create($request);
    }

 /**
     * @OA\Get(
     *      tags={"Users"},
     *      path="/v1/dewtech/users/{id}",
     *      summary="Busca usuário pelo ID",
     *      description="Busca informações do usuário pelo Id.",
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operação realizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado"
     *      )
     * )
     */
    public function show(string $id)
    {
        return $this->userService->getById($id);
    }

    /**
     * @OA\Put(
     *      tags={"Users"},
     *      path="/v1/dewtech/users/{id}",
     *      summary="Atualização de usuário",
     *      description="Atualização de usuário com sucesso.",
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="remember_token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Usuário atualizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="integer"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="remember_token", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *      )
     * )
     */
    public function update(UserRequestsDto $request, string $id)
    {
        return $this->userService->update($id, $request);
    }

   /**
     * @OA\Delete(
     *      tags={"Users"},
     *      path="/v1/dewtech/users/{id}",
     *      summary="Excluir um usuário",
     *      description="Excluir um usuário pelo UserId.",
     *      @OA\Parameter(
     *          name="id",
     *          description="User ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Usuário excluído com sucesso"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuário não encontrado"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *      )
     * )
     */
    public function destroy(string $id)
    {
        return $this->userService->delete($id);
    }
}
