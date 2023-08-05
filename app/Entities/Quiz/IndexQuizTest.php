<?php

namespace App\Entities\Quiz;

/**
 * @OA\Schema()
 */
class IndexQuizTest
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
     * @OA\Property(type="string")
     * @var
     */
    public $status;

    /**
     * @OA\Property(type="string")
     * @var
     */
    public $profession;

    /**
     * @OA\Property(type="string")
     * @var
     */
    public $specialty;

    /**
     * @OA\Property(type="string")
     * @var
     */
    public $course;

    /**
     * @OA\Property(type="integer")
     * @var
     */
    public $duration;
}

