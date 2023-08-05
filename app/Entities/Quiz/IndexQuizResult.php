<?php

namespace App\Entities\Quiz;

/**
 * @OA\Schema()
 */
class IndexQuizResult
{
    /**
     * @OA\Property(type="integer")
     * @var
     */
    public $id;

    /**
     * @OA\Property(type="string")
     * @var
    */
    public $name;

    /**
     * @OA\Property(
     *      type="string",
     *      property="last_name"
     * )
     * @var
    */
    public $lastName;

    /**
     * @OA\Property(type="string")
     * @var
    */
    public $email;

    /**
     * @OA\Property(type="integer")
     * @var
     */
    public $score;

    /**
     * @OA\Property(type="integer")
     * @var
     */
    public $wrong;

    /**
     * @OA\Property(
     *     type="string",
     * )
     * @var
    */
    public $date;
}

