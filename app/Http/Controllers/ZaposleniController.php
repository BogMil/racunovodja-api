<?php

namespace App\Http\Controllers;

use App\Constants\DataValidationErrorMessages;
use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Services\KorisnikService;
use App\Services\ZaposleniService;
use App\Validators\ZaposleniValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZaposleniController extends Controller
{
    private $_zaposleniService;
    private $_korisnikService;
    private $_validator;

    public function __construct(
        ZaposleniService $zaposleniService,
        KorisnikService $korisnikService,
        ZaposleniValidator $zaposleniValidator
    ) {
        $this->middleware('auth:api');
        $this->_zaposleniService = $zaposleniService;
        $this->_korisnikService = $korisnikService;
        $this->_validator = $zaposleniValidator;
    }

    public function index()
    {
        try {
            $data = $this->_zaposleniService->zaposleniKorisnika(
                auth()->user()->id
            );
            return $this->successfullResponse($data);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function store(Request $request)
    {
        $validator = $this->_validator->forCreate($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        return $this->tryStore($validator->validated());
    }

    private function tryStore($validData)
    {
        try {
            $this->_zaposleniService->create($validData);
            return $this->successfullResponse();
        } catch (ZaposleniSaJmbgIliSifromVecPostojiException $e) {
            return $this->failWithErrors($e->getMessage());
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function update(Request $request)
    {
        $validator = $this->_validator->forUpdate($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }
        return $this->tryUpdate($validator->validated());
    }

    private function tryUpdate($validData)
    {
        try {
            $this->_zaposleniService->Update($validData);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function destroy($id)
    {
        $validator = $this->_validator->forDelete(['id' => $id]);
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }
        return $this->tryDestroy($validator->validated());
    }

    private function tryDestroy($validData)
    {
        try {
            $this->_zaposleniService->delete($validData['id']);
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function izdvojNedostajuceJmbgove(Request $request)
    {
        try {
            $jmbgs = $request['jmbgs'];
            $nedostajuci = $this->_zaposleniService->nedostajuciJmbgoviLogovanogKorisnika(
                $jmbgs
            );

            return $this->successfullResponse($nedostajuci);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function izdvojNedostajuceSifre(Request $request)
    {
        try {
            $sifre = $request['sifre'];
            $nedostajuce = $this->_zaposleniService->nedostajuceSifreLogovanogKorisnika(
                $sifre
            );

            return $this->successfullResponse($nedostajuce);
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    public function azurirajEmail(Request $request, $jmbg)
    {
        try {
            $this->_zaposleniService->azurirajEmailZaposlenog(
                $jmbg,
                $request['email']
            );
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
