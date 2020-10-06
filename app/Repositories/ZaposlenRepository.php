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

    public function delete($id)
    {
        $entity = Zaposleni::findOrFail($id);
        $entity->delete();
    }

    public function update($data)
    {
        $employee = Zaposleni::findOrFail($data['id']);
        $employee['ime'] = $data['ime'];
        $employee['prezime'] = $data['prezime'];
        $employee['bankovni_racun'] = $data['bankovni_racun'];
        $employee['email1'] = $data['email1'];
        $employee['email2'] = $data['email2'];
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

    public function getJmbgoveZaposlenihKorisnika($idKorisnika)
    {
        return Zaposleni::where('id_korisnika', $idKorisnika)
            ->pluck('jmbg')
            ->toArray();
    }

    public function getSifreZaposlenihKorisnika($idKorisnika)
    {
        return Zaposleni::where('id_korisnika', $idKorisnika)
            ->pluck('sifra')
            ->toArray();
    }

    public function azurirajEmailZaposlenogKojiRadiZaLogovanogKorisnika(
        $jmbg,
        $email1,
        $idKorisnika
    ) {
        $zaposleni = Zaposleni::where('id_korisnika', $idKorisnika)
            ->where('jmbg', $jmbg)
            ->firstOrFail();

        $zaposleni->email1 = $email1;
        $zaposleni->save();
    }
}
