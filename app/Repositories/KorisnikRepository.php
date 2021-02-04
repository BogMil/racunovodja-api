<?php

namespace App\Repositories;

use App\Constants\DefaultValues;
use App\DetaljiKorisnika;
use App\Korisnik;
use App\LokacijaSkole;
use App\PravaPristupa;
use App\Zaposleni;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KorisnikRepository
{
    public function register($data)
    {
        DB::transaction(function () use ($data) {
            $korisnik = $this->kreirajKorisnika($data);
            $this->kreirajPodrazumevaneRelacijeZaKorisnika($korisnik);
        });
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
            ->addMonths(2)
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
        $pravaPristupa = new PravaPristupa();
        $pravaPristupa->opiro = false;
        $korisnik->pravaPristupa()->save($pravaPristupa);
    }

    public function vecImaZaposlenogSaJmbg($jmbg)
    {
        return Zaposleni::where('id_korisnika', auth()->user()->id)
            ->Where('jmbg', $jmbg)
            ->count() > 0;
    }

    public function vecImaZaposlenogSaSifrom($sifra)
    {
        return Zaposleni::where('id_korisnika', auth()->user()->id)
            ->Where('sifra', $sifra)
            ->count() > 0;
    }

    public function osnovniPodaciKorisnika($idKorisnika)
    {
        return Korisnik::with('pravaPristupa')
            ->where('id', $idKorisnika)
            ->firstOrFail();
    }

    public function detaljiKorisnika($idKorisnika)
    {
        return DetaljiKorisnika::with('opstina')
            ->where('id_korisnika', $idKorisnika)
            ->firstOrFail();
    }

    public function azurirajDetaljeKorisnika($idKorisnika, $data)
    {
        $detalji = DetaljiKorisnika::where(
            'id_korisnika',
            $idKorisnika
        )->firstOrFail();

        $detalji->poreski_identifikacioni_broj =
            $data['poreski_identifikacioni_broj'] ?? '';
        $detalji->maticni_broj = $data['maticni_broj'] ?? '';
        $detalji->id_opstine = $data['id_opstine'];
        $detalji->bankovni_racun = $data['bankovni_racun'] ?? '';
        $detalji->tip_skole = $data['tip_skole'];
        $detalji->sifra_skole = $data['sifra_skole'] ?? '';
        $detalji->naziv_skole = $data['naziv_skole'] ?? '';
        $detalji->mesto = $data['mesto'] ?? '';
        $detalji->ulica_i_broj = $data['ulica_i_broj'] ?? '';
        $detalji->telefon = $data['telefon'] ?? '';
        $detalji->save();
    }
}
