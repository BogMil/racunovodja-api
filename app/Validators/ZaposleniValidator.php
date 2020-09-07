<?php

namespace App\Validators;

use App\Constants\DataValidationErrorMessages;
use App\Services\KorisnikService;
use App\Services\ZaposleniService;
use Illuminate\Support\Facades\Validator;

class ZaposleniValidator
{
    private $_korisnikService;
    private $_zaposleniService;

    public function __construct(
        KorisnikService $korisnikService,
        ZaposleniService $zaposleniService
    ) {
        $this->_korisnikService = $korisnikService;
        $this->_zaposleniService = $zaposleniService;
    }

    public function forCreate($data)
    {
        return Validator::make($data, [
            'ime' => 'bail|required',
            'prezime' => 'bail|required',
            'id_opstine' => '',
            'email' => 'bail|nullable|email',
            'bankovni_racun' => 'bail|required',
            'sifra' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (
                        $this->_korisnikService->vecImaZaposlenogSaSifrom(
                            $value
                        )
                    ) {
                        $fail('Zaposleni sa tom šifrom već postoji.');
                    }
                },
            ],
            'jmbg' => [
                'bail',
                'required',
                'size:13',
                function ($attribute, $value, $fail) {
                    if (
                        $this->_korisnikService->vecImaZaposlenogSaJmbg($value)
                    ) {
                        $fail('Zaposleni sa tim jmbg-om već postoji.');
                    }
                },
            ],
            'aktivan' => '',
        ]);
    }

    public function forUpdate($data)
    {
        return Validator::make($data, [
            'ime' => 'bail|required',
            'prezime' => 'bail|required',
            'id_opstine' => '',
            'email' => 'bail|nullable|email',
            'bankovni_racun' => 'bail|required',
            'sifra' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) use ($data) {
                    $zaposleni = $this->_korisnikService->zaposleniLogovanogKorisnikaSaSifrom(
                        $value
                    );
                    if (count($zaposleni) == 0) {
                        return;
                    } elseif (
                        count($zaposleni) == 1 &&
                        $zaposleni[0]->id == $data['id']
                    ) {
                        return;
                    } else {
                        $fail('Postoji drugi zaposleni sa tom šifrom.');
                    }
                },
            ],
            'jmbg' => [
                'bail',
                'required',
                'size:13',
                function ($attribute, $value, $fail) use ($data) {
                    $zaposleni = $this->_korisnikService->zaposleniLogovanogKorisnikaSaJmbgom(
                        $value
                    );
                    if (count($zaposleni) == 0) {
                        return;
                    } elseif (
                        count($zaposleni) == 1 &&
                        $zaposleni[0]->id == $data['id']
                    ) {
                        return;
                    } else {
                        $fail('Postoji drugi zaposleni sa tim jmbg-om.');
                    }
                },
            ],
            'aktivan' => 'bail|required',
            'id' => [
                function ($attribute, $value, $fail) {
                    if (
                        !$this->_zaposleniService->zaposleniRadiZaLogovanogKorisnika(
                            $value
                        )
                    ) {
                        $fail(DataValidationErrorMessages::neovlasceniPristup);
                    }
                },
            ],
        ]);
    }
}
