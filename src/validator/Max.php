<?php


namespace angletf\validator;



use angletf\Check;

class Max implements Check
{
    public static function check(&$data, $rule_value)
    {
        if(is_numeric($data)){
            return $data <= $rule_value;
        }

        if(is_string($data)){
            $len = mb_strlen($data, 'utf-8');
            return $len <= $rule_value;
        }

        //throw new \Exception("Max Validator error: not a character or a number");
        return false;
    }
}