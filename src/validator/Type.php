<?php


namespace angletf\validator;

use angletf\Check;

class Type implements Check
{
    public static function check(&$data, $rule_value)
    {
        $type = strtolower($rule_value);

        switch ($type) {
            case 'string':
                if (!is_string($data)) return false;
                break;
            case 'int':
                if (filter_var($data, FILTER_VALIDATE_INT) === false) return false;
                $data = filter_var($data, FILTER_VALIDATE_INT);
                break;
            case 'float':
                if (filter_var($data, FILTER_VALIDATE_FLOAT) === false) return false;
                $data = filter_var($data, FILTER_VALIDATE_FLOAT);
                break;
            case 'array':
                if (!is_array($data)) return false;
                break;
            default:
                throw new \Exception("Type Validator error: Unknown type {$type}");
                break;
        }

        return true;
    }

}