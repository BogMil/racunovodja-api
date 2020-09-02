<?php

namespace App\Services;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\KorisnikRepository;
use App\Repositories\ZaposleniRepository;
use App\Zaposleni;

class ZaposleniService
{
    private $_zaposleniRepository;
    private $_korisnikRepository;

    public function __construct(
        ZaposleniRepository $zaposleniRepository,
        KorisnikRepository $korisnikRepository
    ) {
        $this->_zaposleniRepository = $zaposleniRepository;
        $this->_korisnikRepository = $korisnikRepository;
    }

    public function zaposleniKorisnika($idKorisnika)
    {
        return $this->_zaposleniRepository->zaposleniKorisnika($idKorisnika);
    }

    public function create($validData)
    {
        $validData['id_korisnika'] = auth()->user()->id;
        $validData['aktivan'] = $validData['aktivan'] ?? true;

        $this->_zaposleniRepository->create($validData);
    }
}
