<?php

namespace App\Entities\Messages;

/**
 * @OA\Schema()
 */
class DefaultValidationItem
{
    /**
     * @OA\Property(
     *   type="number",
     *   description="Unique ID for each Validation Rule"
     * )
     * @var
     */
    public $code;

    /**
     * @OA\Property(
     *   type="string",
     *   description="Unique Validation Rule name that has been failed"
     * )
     * @var
     */
    public $rule;

    /**
     * @OA\Property(
     *   type="string",
     *   description="Default error message for specified Validation Rule"
     * )
     * @var
     */
    public $message;
}
