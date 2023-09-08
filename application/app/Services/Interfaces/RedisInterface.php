<?php

namespace App\Services\Interfaces;

use App\Http\Requests\Redis\RedisRequestDto;

interface RedisInterface
{
    public function store(RedisRequestDto $redisRequestDto);

    public function get($uuid);

    public function remove($uuid);
}
