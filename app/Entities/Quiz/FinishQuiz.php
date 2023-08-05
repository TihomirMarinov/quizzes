<?php

namespace App\Entities\Quiz;

/**
 * @OA\Schema(
 *     required={
 *         "answers",
 *     }
 * )
 */
class FinishQuiz
{
  /**
     * @OA\Property(
     *      type="array",
     *      description="List IDs, of user answers",
     *      @OA\Items(type="integer")
     * )
     * @var
     */
    public $answers;
}
