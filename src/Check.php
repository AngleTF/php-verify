<?php


namespace angletf;


interface Check
{
    public function check(mixed &$data): bool;
}

