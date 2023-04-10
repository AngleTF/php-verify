<?php

namespace angletf;

enum VerifyType
{
    case String;
    case Int;
    case Float;
    case Array;
    case Json;
    case Base64;
}