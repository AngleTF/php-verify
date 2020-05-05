<?php


namespace angletf\validator;

use angletf\Check;

class Type implements Check
{
    public static function check(&$data, $rule_value, $convert = true)
    {
        $type = strtolower($rule_value);
        $new_data = null;

        switch ($type) {
            case 'string':
                if (!is_string($data)) return false;
                break;
            case 'int':
                if (filter_var($data, FILTER_VALIDATE_INT) === false) return false;
                $new_data = filter_var($data, FILTER_VALIDATE_INT);
                break;
            case 'float':
                if (filter_var($data, FILTER_VALIDATE_FLOAT) === false) return false;
                $new_data = filter_var($data, FILTER_VALIDATE_FLOAT);
                break;
            case 'array':
                if (!is_array($data)) return false;
                break;
            case 'json':
                if (!is_string($data)) return false;
                $new_data = json_decode($data, true);
                if($new_data === null) return false;
                break;
            case 'base64':
                if (!is_string($data)) return false;
                $new_data = base64_decode($data, true);
                if($new_data === false) return false;
                break;
            default:
                throw new \Exception("Type Validator error: Unknown type {$type}");
                break;
        }

        if($new_data !== null && $convert){
            $data = $new_data;
        }

        return true;
    }

}