<?php

namespace App\Http\Validations;

/**
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     @OA\Property(property="status", type="boolean", example="true"),
 *     @OA\Property(property="message", type="string", example="Success message."),
 *     @OA\Property(property="code", type="int64", example=200)
 * )
 */
class SuccessResponse
{

}
