<?php

namespace App\Services;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\KorisnikRepository;
use App\Repositories\ZaposleniRepository;
use App\Zaposleni;

class KorisnikService
{
    private $_korisnikRepository;

    public function __construct(KorisnikRepository $korisnikRepository)
    {
        $this->_korisnikRepository = $korisnikRepository;
    }

    public function vecImaZaposlenogSaJmbg($jmbg)
    {
        return $this->_korisnikRepository->vecImaZaposlenogSaJmbg($jmbg);
    }

    public function vecImaZaposlenogSaSifrom($sifra)
    {
        return $this->_korisnikRepository->vecImaZaposlenogSaSifrom($sifra);
    }
}
