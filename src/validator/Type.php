<?php


namespace angletf\validator;

use angletf\Check;
use angletf\Validator;
use angletf\VerifyType;

class Type extends Validator implements Check
{
    /**
     * @throws \Exception
     */
    public function check(&$data): bool
    {
        if ($data === null) return false;

        $new_data = null;

        switch ($this->value) {
            case VerifyType::String:
                if (!is_string($data)) return false;
                break;
            case VerifyType::Int:
                if (filter_var($data, FILTER_VALIDATE_INT) === false) return false;
                break;
            case VerifyType::Float:
                if (filter_var($data, FILTER_VALIDATE_FLOAT) === false) return false;
                break;
            case VerifyType::Array:
                if (!is_array($data)) return false;
                break;
            case VerifyType::Json:
                if (!is_string($data)) return false;
                $new_data = json_decode($data, true);
                if ($new_data === null && json_last_error() != JSON_ERROR_NONE) return false;
                break;
            case VerifyType::Base64:
                if (!is_string($data)) return false;
                $new_data = base64_decode($data, true);
                if ($new_data === false) return false;
                break;
            default:
                throw new \Exception("Type Validator error: Unknown type {$this->value}");
                break;
        }


        return true;
    }

}