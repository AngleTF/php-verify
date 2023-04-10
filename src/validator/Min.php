<?php


namespace angletf\validator;

use angletf\Check;
use angletf\Validator;

class Min extends Validator implements Check
{
    public function check(&$data): bool
    {
        if ($data === null) return false;

        if (is_numeric($data)) {
            return $data >= $this->value;
        }

        if (is_string($data)) {
            $len = mb_strlen($data, 'utf-8');
            return $len >= $this->value;
        }

        return false;
    }
}