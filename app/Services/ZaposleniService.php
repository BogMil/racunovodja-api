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

    public function delete($id)
    {
        $this->_zaposleniRepository->delete($id);
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

    public function nedostajuciJmbgoviLogovanogKorisnika($jmbgs)
    {
        $postojeciJmbgovi = $this->_zaposleniRepository->getJmbgoveZaposlenihKorisnika(
            auth()->user()->id
        );

        $nedostajuci = [];

        foreach ($jmbgs as $maybeNew) {
            if (in_array($maybeNew, $postojeciJmbgovi)) {
                continue;
            }
            array_push($nedostajuci, $maybeNew);
        }

        return $nedostajuci;
    }

    public function nedostajuceSifreLogovanogKorisnika($sifre)
    {
        $postojeceSifre = $this->_zaposleniRepository->getSifreZaposlenihKorisnika(
            auth()->user()->id
        );

        $nedostajuce = [];

        foreach ($sifre as $maybeNew) {
            if (in_array($maybeNew, $postojeceSifre)) {
                continue;
            }
            array_push($nedostajuce, $maybeNew);
        }

        return $nedostajuce;
    }

    public function azurirajEmailZaposlenog($jmbg, $email)
    {
        $this->_zaposleniRepository->azurirajEmailZaposlenogKojiRadiZaLogovanogKorisnika(
            $jmbg,
            $email,
            auth()->user()->id
        );
    }
}
