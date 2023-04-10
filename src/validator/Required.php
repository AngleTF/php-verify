<?php


namespace angletf\validator;

use angletf\Check;
use angletf\Validator;

class Required extends Validator implements Check
{
    public mixed $default = NULL;

    public function __construct()
    {
        $this->value = false;
    }

    public function check(&$data): bool
    {
        if ($data !== null) {
           return true;
        }

        if ($this->value){
            return false;
        }

        $data = $this->default;
        $this->next = false;

        return true;
    }
}