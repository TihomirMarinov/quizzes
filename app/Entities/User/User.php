<?php

namespace App\Entities\User;

/**
 * @OA\Schema()
 */
class User
{
    /**
     * @OA\Property(type="integer")
     * @var
     */
    public $id;

    /**
     * @OA\Property(type="string", property="name")
     * @var
     */
    public $name;

    /**
     * @OA\Property(type="string", property="email")
     * @var
     */
    public $email;
}
