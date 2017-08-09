<?php

namespace MarkMyers\FFNerd\Exceptions;

use Exception;

class NoWeekArgument extends Exception
{
    public static function create() : NoWeekArgument
    {
        return new static("A week must be passed. i.e. 1");
    }
}
