<?php

use Firebase\JWT\JWT;

if (!function_exists('decode_jwt')) {
    /**
     * Decode JWT without verifying the signature.
     *
     * @param string $jwt
     * @return object|null
     */
    function decode_jwt($jwt)
    {
        try {
            // Split the token
            [$header, $payload, $signature] = explode('.', $jwt);

            // Base64 decode the payload
            $decodedPayload = base64_decode(str_pad(strtr($payload, '-_', '+/'), strlen($payload) % 4, '=', STR_PAD_RIGHT));

            return json_decode($decodedPayload);
        } catch (\Exception $e) {
            return null;
        }
    }
}

