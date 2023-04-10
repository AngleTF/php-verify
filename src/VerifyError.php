<?php

namespace angletf;

class VerifyError
{
    public string $validatorName;
    public Validator $validator;

    public string $paramName;
    public mixed $paramData;
}