<?php

namespace App\Services;

use App\Http\Requests\Redis\RedisRequestDto;
use App\Services\Interfaces\RedisInterface;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Uuid;

class RedisService implements RedisInterface
{
    public function store(RedisRequestDto $redisRequestDto)
    {
        $uuid = Uuid::uuid4()->toString();

        $name = $redisRequestDto->request->get('name');
        $email = $redisRequestDto->request->get('email');

        $dataToStore = [
            'name' => $name,
            'email' => $email
        ];
        // Convertendo o objeto em JSON
        $serializedData = json_encode($dataToStore);

        Redis::set("id:{$uuid}", $serializedData);
        Redis::expire("id:{$uuid}", config('auth.jwt_expiry'));
        return $uuid;
    }
    public function get($uuid)
    {
        return Redis::get("id:{$uuid}");
    }
    public function remove($uuid)
    {
        Redis::del("id:{$uuid}");
    }
}
