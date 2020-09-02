<?php

namespace App\Repositories;

use App\Zaposleni;

class ZaposleniRepository
{
    public function zaposleniKorisnika($idKorisnika)
    {
        return Zaposleni::where('id_korisnika', $idKorisnika)
            ->with('opstina')
            // ->with('podrazumevaneRelacije')
            ->orderBy('aktivan', 'desc')
            ->orderBy('prezime')
            ->orderBy('ime')
            ->get();
    }

    public function create($employeeData)
    {
        $employee = new Zaposleni($employeeData);
        $employee->save();
    }
}
