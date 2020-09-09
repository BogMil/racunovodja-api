<?php

namespace App\Http\Controllers;

use App\Services\KorisnikService;
use Illuminate\Http\Request;

class KorisnikController extends Controller
{
    private $_korisnikService;

    public function __construct(KorisnikService $korisnikService)
    {
        $this->_korisnikService = $korisnikService;
        $this->middleware('auth:api');
    }

    public function detalji()
    {
        try {
            $detalji = $this->_korisnikService->detaljiLogovanogKorisnika();
            return $this->successfullResponse($detalji);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function azurirajDetalje(Request $request)
    {
        try {
            $this->_korisnikService->azurirajDetaljeLogovanogKorisnika(
                $request->all()
            );
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
