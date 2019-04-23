<?php


class VerifyInt
{
    public static function verifyMax($verify_val, $rule_val){
        return $verify_val <= $rule_val;
    }

    public static function verifyMin($verify_val, $rule_val){
        return $verify_val >= $rule_val;
    }
}