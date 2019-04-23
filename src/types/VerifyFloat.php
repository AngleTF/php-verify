<?php

namespace angletf\types;

class VerifyFloat
{
    public static function verifyMax($verify_val, $rule_val){
        return $verify_val <= $rule_val;
    }

    public static function verifyMin($verify_val, $rule_val){
        return $verify_val >= $rule_val;
    }
}