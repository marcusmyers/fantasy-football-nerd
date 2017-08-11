<?php

namespace MarkMyers\FFNerd\Exceptions;

use Exception;

class UndefinedKey extends Exception
{
    public static function api() : UndefinedKey
    {
        return new static("Api key does not exist");
    }
}
