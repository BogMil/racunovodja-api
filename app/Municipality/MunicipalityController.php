<?php

namespace App\Municipality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function index()
    {
        return $this->successfullResponse(Municipality::all());
    }
}
