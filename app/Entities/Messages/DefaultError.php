<?php

namespace App\Entities\Messages;

use App\Entities\Messages\DefaultMessage;

/**
 * @OA\Schema()
 */
class DefaultError extends DefaultMessage
{
    /**
     * @OA\Property(type="object")
     * @var
     */
    public $error;
}
