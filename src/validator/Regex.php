<?php


namespace angletf\validator;

use angletf\Check;
use angletf\Validator;

class Regex extends Validator implements Check
{
    public function check(&$data): bool
    {
        if ($data === null) return false;

        $match_count = preg_match($this->value, $data);
        if($match_count === false){
            throw new \Exception("Regex Validator error: pattern => {$this->value}");
        }
        return $match_count !== 0;
    }
}