<?php

namespace angletf\types;

class VerifyArray
{
    public static function verifyCount($verify_val, $rule_val){
        return count($verify_val) === $rule_val;
    }
}