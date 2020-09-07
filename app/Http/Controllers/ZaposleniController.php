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
        try {
            $employee = $this->_zaposleniService->find($id);
            if ($employee->id_korisnika != auth()->user()->id) {
                return $this->failWithMessage(
                    'Nemate parava pristupa tuđim podacima'
                );
            }

            $employee->delete();
            return $this->successfullResponse();
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    private function tryUpdate($validData)
    {
        try {
            $this->_zaposleniService->Update($validData);
            return $this->successfullResponse();
        } catch (ZaposleniSaJmbgIliSifromVecPostojiException $e) {
            return $this->failWithErrors($e->getMessage());
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }
}
