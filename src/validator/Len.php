<?php


namespace angletf\validator;


use angletf\Check;
use angletf\Validator;

class Len extends Validator implements Check
{
    public function check(mixed &$data): bool
    {
        if ($data === null) return false;
        $data = (string)$data;
        $len = mb_strlen($data, 'utf-8');
        return $len === $this->value;
    }
}