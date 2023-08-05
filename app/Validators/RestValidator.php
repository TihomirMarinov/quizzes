<?php
/**
 * Custom RESTful-aware validation handler
 *
 * @url: https://laracasts.com/discuss/channels/general-discussion/how-to-return-error-code-of-validation-fields-in-rest-api
 */
namespace App\Validators;

Use Illuminate\Support\Facades\App;
use App\Contracts\RuleAlias;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class RestValidator extends Validator {
    // default Laravel validators
    const ERROR_ACCEPTED                          = 101;
    const ERROR_ACTIVE_URL                        = 102;
    const ERROR_AFTER                             = 103;
    const ERROR_AFTER_OR_EQUAL                    = 104;
    const ERROR_ALPHA                             = 105;
    const ERROR_ALPHA_DASH                        = 106;
    const ERROR_ALPHA_NUM                         = 107;
    const ERROR_ARRAY                             = 108;
    const ERROR_BAIL                              = 109;
    const ERROR_BEFORE                            = 110;
    const ERROR_BEFORE_OR_EQUAL                   = 111;
    const ERROR_BETWEEN                           = 112;
    const ERROR_BOOLEAN                           = 113;
    const ERROR_CONFIRMED                         = 114;
    const ERROR_DATE                              = 115;
    const ERROR_DATE_EQUALS                       = 116;
    const ERROR_DATE_FORMAT                       = 117;
    const ERROR_DIFFERENT                         = 118;
    const ERROR_DIGITS                            = 119;
    const ERROR_DIGITS_BETWEEN                    = 120;
    const ERROR_DIMENSIONS                        = 121;
    const ERROR_DISTINCT                          = 122;
    const ERROR_EMAIL                             = 123;
    const ERROR_ENDS_WITH                         = 124;
    const ERROR_EXCLUDE_IF                        = 125;
    const ERROR_EXCLUDE_UNLESS                    = 126;
    const ERROR_EXISTS                            = 127;
    const ERROR_FILE                              = 128;
    const ERROR_FILLED                            = 129;
    const ERROR_GT                                = 130;
    const ERROR_GTE                               = 131;
    const ERROR_IMAGE                             = 132;
    const ERROR_IN                                = 133;
    const ERROR_IN_ARRAY                          = 134;
    const ERROR_INTEGER                           = 135;
    const ERROR_IP                                = 136;
    const ERROR_IPV4                              = 137;
    const ERROR_IPV6                              = 138;
    const ERROR_JSON                              = 139;
    const ERROR_LT                                = 140;
    const ERROR_LTE                               = 141;
    const ERROR_MAX                               = 142;
    const ERROR_MIMETYPES                         = 143;
    const ERROR_MIMES                             = 144;
    const ERROR_MIN                               = 145;
    const ERROR_NOT_IN                            = 146;
    const ERROR_NOT_REGEX                         = 147;
    const ERROR_NULLABLE                          = 148;
    const ERROR_NUMERIC                           = 149;
    const ERROR_PASSWORD                          = 150;
    const ERROR_PRESENT                           = 151;
    const ERROR_REGEX                             = 152;
    const ERROR_REQUIRED                          = 153;
    const ERROR_REQUIRED_IF                       = 154;
    const ERROR_REQUIRED_UNLESS                   = 155;
    const ERROR_REQUIRED_WITH                     = 156;
    const ERROR_REQUIRED_WITH_ALL                 = 157;
    const ERROR_REQUIRED_WITHOUT                  = 158;
    const ERROR_REQUIRED_WITHOUT_ALL              = 159;
    const ERROR_SAME                              = 160;
    const ERROR_SIZE                              = 161;
    const ERROR_STARTS_WITH                       = 162;
    const ERROR_STRING                            = 163;
    const ERROR_TIMEZONE                          = 164;
    const ERROR_UNIQUE                            = 165;
    const ERROR_URL                               = 166;
    const ERROR_UUID                              = 167;
    const ERROR_CSV_IN                            = 168;

    // default and custom rules
    const ERROR_UNKNOWN                           =  -1;
    const ERROR_RECAPTCHA                         = 301;
    const ERROR_PASSWORD_VALID                    = 302;
    const ERROR_LOGIN                             = 303;
    const ERROR_FILE_VALID                        = 304;
    const ERROR_RELATED                           = 305;
    const ERROR_NOT_CLIENT                        = 306;
    const ERROR_DISALLOWED_CHAT_USER              = 307;

    // Keep all rule names lowercased and without dash between words
    static $codesList = [
        // Default and custom rules
        "unknown"                   => self::ERROR_UNKNOWN,
        "recaptcha"                 => self::ERROR_RECAPTCHA,
        "passwordvalid"             => self::ERROR_PASSWORD_VALID,
        "login"                     => self::ERROR_LOGIN,
        "filevalid"                 => self::ERROR_FILE_VALID,
        "related"                   => self::ERROR_RELATED,
        "not_client"                => self::ERROR_NOT_CLIENT,

        // @url: https://laravel.com/docs/6.x/validation#available-validation-rules
        "accepted"                  => self::ERROR_ACCEPTED,
        "activeurl"                 => self::ERROR_ACTIVE_URL,
        "after"                     => self::ERROR_AFTER,
        "afterorequal"              => self::ERROR_AFTER_OR_EQUAL,
        "alpha"                     => self::ERROR_ALPHA,
        "alphadash"                 => self::ERROR_ALPHA_DASH,
        "alphanum"                  => self::ERROR_ALPHA_NUM,
        "array"                     => self::ERROR_ARRAY,
        "bail"                      => self::ERROR_BAIL,
        "before"                    => self::ERROR_BEFORE,
        "beforeorequal"             => self::ERROR_BEFORE_OR_EQUAL,
        "between"                   => self::ERROR_BETWEEN,
        "boolean"                   => self::ERROR_BOOLEAN,
        "confirmed"                 => self::ERROR_CONFIRMED,
        "date"                      => self::ERROR_DATE,
        "dateequals"                => self::ERROR_DATE_EQUALS,
        "dateformat"                => self::ERROR_DATE_FORMAT,
        "different"                 => self::ERROR_DIFFERENT,
        "digits"                    => self::ERROR_DIGITS,
        "digitsbetween"             => self::ERROR_DIGITS_BETWEEN,
        "dimensions"                => self::ERROR_DIMENSIONS,
        "distinct"                  => self::ERROR_DISTINCT,
        "email"                     => self::ERROR_EMAIL,
        "endswith"                  => self::ERROR_ENDS_WITH,
        "excludeif"                 => self::ERROR_EXCLUDE_IF,
        "excludeunless"             => self::ERROR_EXCLUDE_UNLESS,
        "exists"                    => self::ERROR_EXISTS,
        "file"                      => self::ERROR_FILE,
        "filled"                    => self::ERROR_FILLED,
        "gt"                        => self::ERROR_GT,
        "gte"                       => self::ERROR_GTE,
        "image"                     => self::ERROR_IMAGE,
        "in"                        => self::ERROR_IN,
        "inarray"                   => self::ERROR_IN_ARRAY,
        "integer"                   => self::ERROR_INTEGER,
        "ip"                        => self::ERROR_IP,
        "ipv4"                      => self::ERROR_IPV4,
        "ipv6"                      => self::ERROR_IPV6,
        "json"                      => self::ERROR_JSON,
        "lt"                        => self::ERROR_LT,
        "lte"                       => self::ERROR_LTE,
        "max"                       => self::ERROR_MAX,
        "mimetypes"                 => self::ERROR_MIMETYPES,
        "mimes"                     => self::ERROR_MIMES,
        "min"                       => self::ERROR_MIN,
        "notin"                     => self::ERROR_NOT_IN,
        "notregex"                  => self::ERROR_NOT_REGEX,
        "nullable"                  => self::ERROR_NULLABLE,
        "numeric"                   => self::ERROR_NUMERIC,
        "password"                  => self::ERROR_PASSWORD,
        "present"                   => self::ERROR_PRESENT,
        "regex"                     => self::ERROR_REGEX,
        "required"                  => self::ERROR_REQUIRED,
        "requiredif"                => self::ERROR_REQUIRED_IF,
        "requiredunless"            => self::ERROR_REQUIRED_UNLESS,
        "requiredwith"              => self::ERROR_REQUIRED_WITH,
        "requiredwithall"           => self::ERROR_REQUIRED_WITH_ALL,
        "requiredwithout"           => self::ERROR_REQUIRED_WITHOUT,
        "requiredwithoutall"        => self::ERROR_REQUIRED_WITHOUT_ALL,
        "same"                      => self::ERROR_SAME,
        "size"                      => self::ERROR_SIZE,
        "startswith"                => self::ERROR_STARTS_WITH,
        "string"                    => self::ERROR_STRING,
        "timezone"                  => self::ERROR_TIMEZONE,
        "unique"                    => self::ERROR_UNIQUE,
        "url"                       => self::ERROR_URL,
        "uuid"                      => self::ERROR_UUID,
        "csvin"                     => self::ERROR_CSV_IN,
        "isalloweduser"             => self::ERROR_DISALLOWED_CHAT_USER,


    ];

    public function addFailure($attribute, $rule, $parameters = [])
    {
        if (!$this->messages) {
            $this->passes();
        }

        $message = $this->getMessage($attribute, $rule);
        $message = $this->makeReplacements($message, $attribute, $rule, $parameters);

        $this->addMessageBag($rule, $message, $attribute, $parameters);
    }

    protected function validateUsingCustomRule($attribute, $value, $rule)
    {
        $attribute = $this->replacePlaceholderInString($attribute);

        $value = is_array($value) ? $this->replacePlaceholders($value) : $value;

        if ($rule instanceof ValidatorAwareRule) {
            $rule->setValidator($this);
        }

        if ($rule instanceof DataAwareRule) {
            $rule->setData($this->data);
        }

        if (! $rule->passes($attribute, $value)) {
            $this->failedRules[$attribute][get_class($rule)] = [];

            $message = $rule->message();
            $message = (string) (is_array($message) ? $message[0] : $message);
            $message = $this->makeReplacements($message, $attribute, get_class($rule), []);

            $ruleClassName = explode('\\', get_class($rule));
            $ruleClassName = $ruleClassName[count($ruleClassName) - 1];
            $ruleName = $rule instanceof RuleAlias
                ? $rule->ruleAs()
                : $ruleClassName ?? self::ERROR_UNKNOWN;

            return $this->addMessageBag($ruleName, $message, $attribute, []);
        }
    }

    private function addMessageBag($rule, $message, $attribute, $parameters)
    {
        $request = App::make('request');
        if (!$request->wantsJson()) {
            $this->messages->add($attribute, $message);
            $this->failedRules[$attribute][$rule] = $parameters;
            return;
        }

        $customMessage = new MessageBag();
        $rule = strtolower($rule); // we will use lowercase-only from now one

        $code = isset(RestValidator::$codesList[$rule])
            ? RestValidator::$codesList[$rule]
            : RestValidator::$codesList['unknown'];

        $customMessage->merge(['code' => $code]);
        $customMessage->merge(['rule' => $rule]);
        $customMessage->merge(['message' => $message]);

        $this->messages->add($attribute, $customMessage);

        $this->failedRules[$attribute][$rule] = $parameters;
    }
}
