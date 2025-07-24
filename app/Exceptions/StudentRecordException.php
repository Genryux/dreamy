<?php

namespace App\Exceptions;

use Exception;

class StudentRecordException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message = 'Something went wrong.', $code = 422)
    {
        parent::__construct($message, $code);
    }
}
