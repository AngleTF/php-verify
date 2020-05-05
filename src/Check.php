<?php


namespace angletf;


interface Check
{
    public static function check(&$data, $rule_value, $convert);
}