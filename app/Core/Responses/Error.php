<?php

namespace App\Core\Responses;

class Error
{
    function __construct($message)
    {
        $this->message = $message;
    }
    public $status = "error";
    public $message;
}
