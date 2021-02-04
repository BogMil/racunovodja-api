<?php

namespace App\Http\Controllers;

use App\Services\SlanjeMailovaService;
use App\SlanjeMailaLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getLog(SlanjeMailaLog $slanjeMailaLog)
    {
        try {
            $json = json_decode($slanjeMailaLog->rezultat_slanja);
            return $this->successfullResponse($json);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function getLogs()
    {
        try {
            $logs = SlanjeMailaLog::select(
                DB::raw('TIME(`created_at`) as created_at'),
                'subject',
                'uspesno',
                'email_korisnika',
                'id'
            )
                ->where('email_korisnika', auth()->user()->email)
                ->whereNotNull('rezultat_slanja')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->successfullResponse($logs);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
