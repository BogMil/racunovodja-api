<?php

namespace App\Core\Responses;

class Fail
{
    public $status = "fail";
    public $data;
    public $message;

    public static function withMessage($message)
    {
        $fail = new Fail();
        $fail->message = $message;
        return $fail;
    }
}
