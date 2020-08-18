<?php

namespace App\Repositories;

use App\Constants\DefaultValues;
use App\DetaljiKorisnika;
use App\Korisnik;
use App\LokacijaSkole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class KorisnikRepository
{
    public function register($data)
    {
        $korisnik = $this->kreirajKorisnika($data);
        $this->kreirajPodrazumevaneRelacijeZaKorisnika($korisnik);
    }

    private function kreirajKorisnika($data)
    {
        $korisnik = new Korisnik($this->dodajKalkulisanaPolja($data));
        $korisnik->save();
        return $korisnik;
    }

    private function dodajKalkulisanaPolja($data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['validan_do'] = $this->izracunajKrajnjiDatumProbnogPerioda();
        return $data;
    }

    private function izracunajKrajnjiDatumProbnogPerioda()
    {
        return Carbon::now()
            ->addYears(1)
            ->addDays(1)
            ->format('Y-m-d h:i:s');
    }

    private function kreirajPodrazumevaneRelacijeZaKorisnika(Korisnik $korisnik)
    {
        $korisnik->detalji()->save(new DetaljiKorisnika());
        $korisnik->lokacijeSkole()->save(
            new LokacijaSkole([
                'naziv' => DefaultValues::PODRAZUMEVANA_LOKACIJA_SKOLE,
            ])
        );
    }
}
