<?php

namespace App\Exceptions;

use App\Validators\RestValidator;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as ValidatorFacade;

class CustomValidationException extends ValidationException
{
    public $status = 400;

    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param  array  $messages
     * @return static
     */
    public static function withMessages(array $messages)
    {
        return new static(tap(ValidatorFacade::make([], []), function ($validator) use ($messages) {
            foreach ($messages as $key => $value) {
                foreach (Arr::wrap($value) as $message) {
                    if (is_string($message)) {
                        $validator->errors()->add($key, $message);
                    } else {
                        $customMessage = new MessageBag();

                        $rule = strtolower($message['rule'] ?? 'unknown');
                        $code = $message['code']
                            ?? RestValidator::$codesList[$rule]
                            ?? RestValidator::$codesList['unknown'];
                        $messageText = $message['message'] ?? trans('validation.default');

                        $customMessage->merge(['code' => $code]);
                        $customMessage->merge(['rule' => $rule]);
                        $customMessage->merge(['message' =>  $messageText]);

                        $validator->messages()->add($key, $customMessage);
                    }
                }
            }
        }));
    }
}
