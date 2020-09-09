<?php

namespace App\Http\Controllers;

use App\Services\KorisnikService;
use App\Validators\KorisnikValidator;
use Illuminate\Http\Request;

class KorisnikController extends Controller
{
    private $_korisnikService;
    private $_validator;

    public function __construct(
        KorisnikService $korisnikService,
        KorisnikValidator $validator
    ) {
        $this->_korisnikService = $korisnikService;
        $this->_validator = $validator;
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
        $validator = $this->_validator->zaAzuriranjeDetalja($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return $this->tryAzurirajDetalje($validator->validated());
    }

    public function tryAzurirajDetalje($validData)
    {
        try {
            $this->_korisnikService->azurirajDetaljeLogovanogKorisnika(
                $validData
            );
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
