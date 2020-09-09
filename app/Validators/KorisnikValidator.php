<?php

namespace App\Validators;
use Illuminate\Support\Facades\Validator;

class KorisnikValidator
{
    public function zaAzuriranjeDetalja($data)
    {
        return Validator::make($data, [
            'id' => [''],
            'poreski_identifikacioni_broj' => [''],
            'maticni_broj' => [''],
            'id_korisnika' => ['bail', 'required'],
            'id_opstine' => [''],
            'bankovni_racun' => [''],
            'tip_skole' => [''],
            'sifra_skole' => [''],
            'naziv_skole' => [''],
            'mesto' => [''],
            'ulica_i_broj' => [''],
            'telefon' => [''],
        ]);
    }
}
