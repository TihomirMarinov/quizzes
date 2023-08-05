<?php

namespace App\Entities\Input;

/**
 * @OA\Schema(
 *   required={"email", "password"}
 * )
 */
class InputLogin
{
    /**
     * @OA\Property(type="string", title="E-mail address")
     * @var
     */
    public $email;

    /**
     * @OA\Property(type="string")
     * @var
     */
    public $password;
}
