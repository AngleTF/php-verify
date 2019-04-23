<?php

namespace angletf\types;

class VerifyString
{
    public static function verifyLength($verify_val, $rule_val){
        return mb_strlen($verify_val, 'utf-8') == $rule_val;
    }

    public static function verifyRegex($verify_val, $rule_val){
        return preg_match($rule_val, $verify_val) != 0;
    }
}