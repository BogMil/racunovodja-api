<?php

namespace App\Validators;

use App\Constants\DataValidationErrorMessages;
use App\Services\KorisnikService;
use App\Services\ZaposleniService;
use Illuminate\Support\Facades\Validator;

class KorisnikValidator
{
    public function zaAzuriranjeDetalja($data)
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
}
