<?php

namespace App\Entities\Messages;

use App\Entities\Messages\DefaultMessage;

/**
 * @OA\Schema()
 */
class DefaultBadRequest extends DefaultMessage
{
    /**
     * @OA\Property(
     *  type="object",
     *  @OA\Property(
     *    property="field_name",
     *    description="The Field name (see request body). Each field could have 1 or more error messages.",
     *    type="array",
     *    @OA\Items(ref="#/components/schemas/DefaultValidationItem"),
     *  ),
     * )
     * @var
     */
    public $errors;
}
