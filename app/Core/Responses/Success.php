<?php

namespace App\Core\Responses;

use App\Constants\ResponseStatuses;

class Success
{
    function __construct($data = null)
    {
        $this->data = $data;
    }

    public $status = ResponseStatuses::SUCCESS;
    public $data;
}
