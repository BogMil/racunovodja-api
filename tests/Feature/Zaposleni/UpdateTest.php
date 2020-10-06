<?php

namespace Tests\Feature\Zaposleni;

use App\Constants\DataValidationErrorMessages;
use App\Korisnik;
use App\Opstina;
use App\Zaposleni;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/zaposleni';
    private $createData;
    private $updateData;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->updateData = [
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email@adresa.com',
            'email2' => 'email@adresa.com',
            'sifra' => '2345',
            'jmbg' => '1231231231231',
            'aktivan' => true,
            'id_opstine' => '1',
            'id' => '1',
        ];
    }

    /** @test */
    public function ZaposleniMozeDaSeAzurira()
    {
        $this->kreirajPrvogKorisnikaIZaposlenog();
        $this->withJwt();

        $response = $this->put($this->url . "/1", [
            'ime' => 'Novo Ime',
            'prezime' => 'Novo Prezime',
            'bankovni_racun' => 'novi racun',
            'email1' => 'nova.email@adresa.com',
            'email2' => 'nova2.email@adresa.com',
            'sifra' => 'nova',
            'jmbg' => '0000000000000',
            'aktivan' => false,
            'id_opstine' => '2',
            'id' => '1',
        ]);

        $response->assertOk();
        $zaposleni = Zaposleni::first();
        $this->assertEquals($zaposleni->ime, "Novo Ime");
        $this->assertEquals($zaposleni->prezime, "Novo Prezime");
        $this->assertEquals($zaposleni->bankovni_racun, "novi racun");
        $this->assertEquals($zaposleni->email1, "nova.email@adresa.com");
        $this->assertEquals($zaposleni->email2, "nova2.email@adresa.com");
        $this->assertEquals($zaposleni->sifra, "nova");
        $this->assertEquals($zaposleni->jmbg, "0000000000000");
        $this->assertEquals($zaposleni->aktivan, 0);
        $this->assertEquals($zaposleni->id_opstine, '2');
    }

    /** @test */
    public function nijeMoguceAzuriranjeTudjegZaposlenog()
    {
        $this->kreirajPrvogKorisnikaIZaposlenog();
        $this->kreirajDrugogKorisnikaIZaposlenog();

        $this->updateData['id'] = 2;
        $this->withJwt();
        $response = $this->put($this->url . "/2", $this->updateData);
        $response->assertStatus(400);

        $this->assertArrayHasKey('errors', $response->decodeResponseJson());
        $this->assertArrayHasKey(
            'id',
            $response->decodeResponseJson()['errors']
        );

        $this->assertContains(
            DataValidationErrorMessages::neovlasceniPristup,
            $response->decodeResponseJson()['errors']['id']
        );
    }

    /** @test */
    public function imeJeObavezno()
    {
        $this->poljeJeObavezno('ime');
    }

    /** @test */
    public function prezimeJeObavezno()
    {
        $this->poljeJeObavezno('prezime');
    }

    /** @test */
    public function jmbgJeObavezno()
    {
        $this->poljeJeObavezno('jmbg');
    }

    /** @test */
    public function sifraJeObavezno()
    {
        $this->poljeJeObavezno('sifra');
    }

    /** @test */
    public function aktivanJeObavezno()
    {
        $this->poljeJeObavezno('aktivan');
    }

    /** @test */
    public function bankovniRacunJeObavezno()
    {
        $this->poljeJeObavezno('bankovni_racun');
    }

    /** @test */
    public function email1NijeObaveznoPolje()
    {
        $this->poljeNijeObavezno('email1');
    }

    /** @test */
    public function email1MoraBitiValidan()
    {
        $this->withJwt();
        $this->kreirajPrvogKorisnikaIZaposlenog();

        $this->updateData['email1'] = 'nevalidan format email-a';
        TestUtils::assertFieldIsEmail('email1', $this->getResponseCallback());
    }

    /** @test */
    public function email2NijeObaveznoPolje()
    {
        $this->poljeNijeObavezno('email2');
    }

    /** @test */
    public function email2MoraBitiValidan()
    {
        $this->withJwt();
        $this->kreirajPrvogKorisnikaIZaposlenog();

        $this->updateData['email2'] = 'nevalidan format email-a';
        TestUtils::assertFieldIsEmail('email2', $this->getResponseCallback());
    }

    /** @test */
    public function nijeMoguceUpdateAkoNekoDrugiVecImaIstiJmbg()
    {
        $this->withJwt();
        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email@adresa.com',

            'sifra' => '1111',
            'jmbg' => '1111111111111',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
        ]);

        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email2' => 'email@adresa.com',
            'sifra' => '222',
            'jmbg' => '2222222222222',
            'id_opstine' => '1',
            'id' => '2',
            'id_korisnika' => '1',
        ]);

        $response = $this->put($this->url . '/1', [
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email@adresa.com',
            'sifra' => '1111',
            'jmbg' => '2222222222222',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
            'aktivan' => true,
        ]);
        $this->assertContains(
            "Postoji drugi zaposleni sa tim jmbg-om.",
            $response->decodeResponseJson()['errors']['jmbg']
        );
    }

    /** @test */
    public function nijeMoguceUpdateAkoNekoDrugiVecImaIstuSifru()
    {
        $this->withJwt();
        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email@adresa.com',
            'sifra' => '1111',
            'jmbg' => '1111111111111',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
        ]);

        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email2' => 'email@adresa.com',
            'sifra' => '222',
            'jmbg' => '2222222222222',
            'id_opstine' => '1',
            'id' => '2',
            'id_korisnika' => '1',
        ]);

        $response = $this->put($this->url . '/1', [
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email@adresa.com',
            'sifra' => '222',
            'jmbg' => '1111111111111',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
            'aktivan' => true,
        ]);
        $this->assertContains(
            "Postoji drugi zaposleni sa tom šifrom.",
            $response->decodeResponseJson()['errors']['sifra']
        );
    }

    private function kreirajPrvogKorisnikaIZaposlenog()
    {
        $this->post('api/auth/registracija', [
            'naziv' => 'Naziv skole',
            'ulica_i_broj' => 'Naziv ulice i broj',
            'grad' => 'naziv grada',
            'email' => 'email@adresa.com',
            'password' => 'lozinka',
            'password_confirmation' => 'lozinka',
            'telefon' => '123456789',
        ]);

        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email1@adresa.com',
            'email2' => 'email2@adresa.com',
            'sifra' => '2345',
            'jmbg' => '1231231231231',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
        ]);
    }

    private function kreirajDrugogKorisnikaIZaposlenog()
    {
        $this->post('api/auth/registracija', [
            'naziv' => 'Naziv skole',
            'ulica_i_broj' => 'Naziv ulice i broj',
            'grad' => 'naziv grada',
            'email' => 'email2@adresa.com',
            'password' => 'lozinka',
            'password_confirmation' => 'lozinka',
            'telefon' => '123456789',
        ]);

        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email1' => 'email1@adresa.com',
            'email2' => 'email2@adresa.com',
            'sifra' => '2345',
            'jmbg' => '1231231231231',
            'id_opstine' => '1',
            'id' => '2',
            'id_korisnika' => '2',
        ]);
    }

    private function poljeJeObavezno($nazivPolja)
    {
        $this->withJwt();
        $this->kreirajPrvogKorisnikaIZaposlenog();
        $this->updateData[$nazivPolja] = '';
        $this->assertCount(1, Zaposleni::all());
        TestUtils::assertFieldIsRequired(
            $nazivPolja,
            $this->getResponseCallback()
        );
    }

    private function poljeNijeObavezno($nazivPolja)
    {
        $this->withJwt();
        $this->kreirajPrvogKorisnikaIZaposlenog();
        $this->updateData[$nazivPolja] = '';
        $response = $this->put($this->url . '/1', $this->updateData);

        $response->assertOK();
        $this->assertCount(1, Zaposleni::all());
    }

    private function getResponseCallback()
    {
        return function () {
            return $this->put($this->url . '/1', $this->updateData);
        };
    }
}
