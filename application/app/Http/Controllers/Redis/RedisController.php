<?php

namespace App\Http\Controllers\Redis;

use App\Http\Requests\Redis\RedisRequestDto;
use App\Services\RedisService;
use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class RedisController extends Controller
{
    protected $redisService;

    public function __construct(RedisService $redisService) {
        $this->redisService = $redisService;
    }

    /**
     * @OA\Post(
     *      tags={"Redis"},
     *      path="/v1/dewtech/redis",
     *      summary="Envia dados para o Redis",
     *      description="Envia dados para o Redi.",
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Dados enviado para o Redis com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="uuid", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error",
     *          @OA\JsonContent(ref="#/components/schemas/Error")
     *      )
     * )
     */
    public function store(RedisRequestDto $request)
    {
        return $this->redisService->store($request);
    }

    /**
     * @OA\Get(
     *      tags={"Redis"},
     *      path="/v1/dewtech/redis/{id}",
     *      summary="Busca dados no Redis",
     *      description="Busca informações no Redis pela key.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Chave de registro no Redis",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operação realizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Registro não encontrado"
     *      )
     * )
     */
    public function show(string $id)
    {
        return $this->redisService->get($id);
    }
    /**
     * @OA\Delete(
     *      tags={"Redis"},
     *      path="/v1/dewtech/redis/{id}",
     *      summary="Excluir registro no Redis",
     *      description="Excluir registro no Redis pela key do redis.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Chave de registro no Redis",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Registro Excluído com sucesso"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Registro não encontrado"
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
        return $this->redisService->remove($id);
    }
}
