<?php

namespace App\Services;

use App\Exceptions\ZaposleniSaJmbgIliSifromVecPostojiException;
use App\Repositories\KorisnikRepository;
use App\Repositories\ZaposlenRepository;
use App\Zaposleni;

class ZaposleniService
{
    private $_zaposleniRepository;
    private $_korisnikRepository;

    public function __construct(
        ZaposlenRepository $zaposleniRepository,
        KorisnikRepository $korisnikRepository
    ) {
        $this->_zaposleniRepository = $zaposleniRepository;
        $this->_korisnikRepository = $korisnikRepository;
    }

    public function zaposleniKorisnika($idKorisnika)
    {
        return $this->_zaposleniRepository->zaposleniKorisnika($idKorisnika);
    }

    public function find($id)
    {
        return $this->_zaposleniRepository->find($id);
    }

    public function create($validData)
    {
        $validData['id_korisnika'] = auth()->user()->id;
        $validData['aktivan'] = $validData['aktivan'] ?? true;

        $this->_zaposleniRepository->create($validData);
    }

    public function update($validData)
    {
        $this->_zaposleniRepository->update($validData);
    }

    public function zaposleniRadiZaLogovanogKorisnika($idZaposlenog)
    {
        $entity = $this->_zaposleniRepository->find($idZaposlenog);
        return auth()->user()->id == $entity->id_korisnika;
    }

    public function zaposleniLogovanogKorisnikaSaJmbgom($jmbg)
    {
        return $this->_zaposleniRepository->zaposleniKorisnikaSaJmbgom(
            auth()->user()->id,
            $jmbg
        );
    }

    public function zaposleniLogovanogKorisnikaSaSifrom($sifra)
    {
        return $this->_zaposleniRepository->zaposleniKorisnikaSaSifrom(
            auth()->user()->id,
            $sifra
        );
    }
}
