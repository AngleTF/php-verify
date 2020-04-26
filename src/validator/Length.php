<?php


namespace angletf\validator;



use angletf\Check;

class Length implements Check
{
    public static function check(&$data, $rule_value)
    {
        $source_data = $data;
        if(is_numeric($source_data)){
            $source_data = (string)$data;
        }

        if(is_string($source_data)){
            $len = mb_strlen($source_data, 'utf-8');
            return $len === $rule_value;
        }

        return false;
    }
}