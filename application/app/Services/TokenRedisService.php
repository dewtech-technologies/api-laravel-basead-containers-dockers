<?php

namespace App\Services;

use App\Services\Interfaces\TokenRedisInterface;
use Illuminate\Support\Facades\Redis;

class TokenRedisService implements TokenRedisInterface
{
    public function store($jti, $token)
    {
        Redis::set("TokenId:{$jti}:jwt", $token);
        Redis::expire("TokenId:{$jti}:jwt", config('auth.jwt_expiry'));
    }

    public function get($jti)
    {
        return Redis::get("TokenId:{$jti}:jwt");
    }

    public function remove($jti)
    {
        Redis::del("TokenId:{$jti}:jwt");
    }
}
