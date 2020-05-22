<?php

namespace App\Municipality;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successfullResponse(Municipality::all());
    }
}
