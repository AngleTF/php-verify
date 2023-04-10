<?php

namespace angletf;

class Validator
{
    public string $paramName;
    public mixed $paramData;
    public mixed $value;
    public \Exception $error;
    public bool $next = true;

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function next(): bool{
        return $this->next;
    }
}