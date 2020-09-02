<?php

namespace App\Services;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\ZaposleniRepository;
use App\Zaposleni;

class ZaposleniService
{
    private $_zaposleniRepository;
    public function __construct(ZaposleniRepository $zaposleniRepository)
    {
        $this->_zaposleniRepository = $zaposleniRepository;
    }

    public function zaposleniKorisnika($idKorisnika)
    {
        return $this->_zaposleniRepository->zaposleniKorisnika($idKorisnika);
    }

    public function create($validData)
    {
        if (
            $this->korisnikVecImaZaposlenogSaJmbgIliSifrom(
                $validData['jmbg'],
                $validData['sifra']
            )
        ) {
            throw new ZaposleniSaJmbgIliSifromVecPostojiException();
        }

        $validData['id_korisnika'] = auth()->user()->id;
        $validData['aktivan'] = $validData['aktivan'] ?? true;

        $this->_zaposleniRepository->create($validData);
        dd(auth()->user()->zaposleni);
    }

    private function korisnikVecImaZaposlenogSaJmbgIliSifrom($jmbg, $sifra)
    {
        return auth()
            ->user()
            ->zaposleni->filter(function ($emp) use ($jmbg, $sifra) {
                return $emp->sifra == $sifra || $emp->jmbg == $jmbg;
            })
            ->count() > 0;
    }
}
