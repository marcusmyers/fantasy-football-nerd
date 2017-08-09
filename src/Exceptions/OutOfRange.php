<?php

namespace MarkMyers\FFNerd\Exceptions;

use Exception;

class OutOfRange extends Exception
{
    public static function create() : OutOfRange
    {
        return new static("Must include a bye week between 4 and 12");
    }
}
