<?php

namespace SspStaff\ADT\Exceptions;

use Throwable;

class PatternMatchException extends \RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
