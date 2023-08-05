<?php

namespace App\Entities\Quiz;

/**
 * @OA\Schema(
 *     required={
 *         "question",
 *     }
 * )
 */
class InputQuiz
{
    /**
     * @OA\Property(
     *     type="string",
     *     maxLength=255,
     * )
     * @var
     */
    public $question;
}

