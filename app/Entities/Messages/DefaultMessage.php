<?php

namespace App\Entities\Messages;

/**
 * @OA\Schema(
 *   required={"message"},
 * )
 */
class DefaultMessage
{
    /**
     * @OA\Property(type="string")
     * @var
     */
    public $message;

}
