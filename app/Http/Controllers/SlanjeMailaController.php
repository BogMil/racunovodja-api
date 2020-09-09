<?php

namespace App\Http\Controllers;

use App\Services\SlanjeMailovaService;
use Illuminate\Http\Request;

class SlanjeMailaController extends Controller
{
    private $_slanjeMailovaService;
    public function __construct(SlanjeMailovaService $slanjeMailovaService)
    {
        $this->middleware('auth:api');
        $this->_slanjeMailovaService = $slanjeMailovaService;
    }

    public function log(Request $request)
    {
        try {
            $this->_slanjeMailovaService->log($request->all());
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
