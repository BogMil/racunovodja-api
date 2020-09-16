<?php

namespace App\Validators;
use Illuminate\Support\Facades\Validator;

class AuthValidator
{
    public function zaLogin($data)
    {
        return Validator::make($data, [
            'email' => 'bail|required|email',
            'password' => 'bail|required',
        ]);
    }

    public function zaRegister($data)
    {
        return Validator::make($data, [
            'naziv' => 'bail|required',
            'ulica_i_broj' => 'bail|required',
            'grad' => 'bail|required',
            'email' => 'bail|required|unique:korisnici|email',
            'password' => 'bail|required|confirmed',
            'telefon' => 'bail|required',
        ]);
    }
}
