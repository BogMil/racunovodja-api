<?php

namespace App\Http\Controllers;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\ZaposleniRepository;
use App\Services\ZaposleniService;
use App\Zaposleni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZaposleniController extends Controller
{
    private $_zaposleniService;
    public function __construct(ZaposleniService $zaposleniService)
    {
        $this->middleware('auth:api');
        $this->_zaposleniService = $zaposleniService;
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
        $validator = $this->getCreateZaposleniDataValidator($request->all());
        if ($validator->fails()) {
            return $this->failWithValidationErrors($validator->errors());
        }

        try {
            $this->_zaposleniService->create($validator->validated());
            return $this->successfullResponse();
        } catch (ZaposleniSaJmbgIliSifromVecPostojiException $e) {
            return $this->failWithErrors($e->getMessage());
        } catch (\Exception $e) {
            return $this->systemErrorResponse($e);
        }
    }

    private function getCreateZaposleniDataValidator($data)
    {
        return Validator::make($data, [
            'ime' => 'bail|required',
            'prezime' => 'bail|required',
            'id_opstine' => '',
            'email' => 'bail|nullable|email',
            'bankovni_racun' => 'bail|required',
            'sifra' => 'bail|required',
            'jmbg' => 'bail|required|size:13',
            'aktivan' => '',
        ]);
    }
}
