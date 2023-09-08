<?php

namespace App\Services\Interfaces;

interface TokenRedisInterface
{
    public function store($userId, $token);

    public function get($userId);

    public function remove($userId);
}
