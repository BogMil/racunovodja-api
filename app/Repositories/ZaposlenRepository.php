<?php

namespace App\Repositories;

use App\Zaposleni;

class ZaposlenRepository
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

    public function update($data)
    {
        $employee = Zaposleni::findOrFail($data['id']);
        $employee['ime'] = $data['ime'];
        $employee['prezime'] = $data['prezime'];
        $employee['bankovni_racun'] = $data['bankovni_racun'];
        $employee['email'] = $data['email'];
        $employee['sifra'] = $data['sifra'];
        $employee['jmbg'] = $data['jmbg'];
        $employee['aktivan'] = $data['aktivan'];
        $employee['id_opstine'] = $data['id_opstine'];
        $employee->save();
    }

    public function find($id)
    {
        return Zaposleni::findOrFail($id);
    }

    public function zaposleniKorisnikaSaJmbgom($idKorisnika, $jmbg)
    {
        return Zaposleni::where('id_korisnika', $idKorisnika)
            ->where('jmbg', $jmbg)
            ->get();
    }

    public function zaposleniKorisnikaSaSifrom($idKorisnika, $sifra)
    {
        return Zaposleni::where('id_korisnika', $idKorisnika)
            ->where('sifra', $sifra)
            ->get();
    }
}
