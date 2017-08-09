<?php

namespace MarkMyers\FFNerd\Exceptions;

use Exception;

class NoPositionArgument extends Exception
{
    public static function create() : NoPositionArgument
    {
        return new static("A position must be passed. i.e. QB");
    }
}
