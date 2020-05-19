<?php

namespace App\Core\Responses;

class Success
{
    function __construct($data = null)
    {
        $this->data = $data;
    }

    public $status = "success";
    public $data;
}
