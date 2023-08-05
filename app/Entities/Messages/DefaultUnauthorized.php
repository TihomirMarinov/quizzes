<?php

namespace App\Entities\Messages;

/**
 * @OA\Schema()
 */
class DefaultUnauthorized
{
    /**
     * @OA\Property(type="string")
     * @var
     */
    public $message;
}
