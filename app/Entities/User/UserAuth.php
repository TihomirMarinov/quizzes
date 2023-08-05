<?php

namespace App\Entities\User;

/**
 * @OA\Schema()
 */
class UserAuth
{
    /**
     * @OA\Property(type="object", ref="#/components/schemas/User")
     * @var
     */
    public $user;

    /**
     * @OA\Property(type="string")
     * @var
     */
    public $token;
}
