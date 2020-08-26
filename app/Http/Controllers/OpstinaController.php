<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Opstina;
use Illuminate\Http\Request;

class OpstinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return $this->successfullResponse(Opstina::all());
    }
}
