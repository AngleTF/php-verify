<?php


namespace angletf\validator;

use angletf\Check;

class Regex implements Check
{
    public static function check(&$data, $rule_value, $convert = true)
    {
        $match_count = preg_match($rule_value, $data);
        if($match_count === false){
            throw new \Exception("Regex Validator error: pattern => {$rule_value}");
        }
        return $match_count !== 0;
    }
}