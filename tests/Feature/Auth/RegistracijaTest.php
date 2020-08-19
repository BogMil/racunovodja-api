<?php

namespace Tests\Feature\Auth;

use App\Constants\ResponseStatuses;
use App\Constants\Statuses;
use App\DetaljiKorisnika;
use App\Korisnik;
use App\LokacijaSkole;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\TestUtils;

class RegistracijaTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/auth/registracija';
    private $requestData = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->requestData = [
            'naziv' => 'Naziv skole',
            'ulica_i_broj' => 'Naziv ulice i broj',
            'grad' => 'naziv grada',
            'email' => 'email@adresa.com',
            'password' => 'lozinka',
            'password_confirmation' => 'lozinka',
            'telefon' => '123456789',
        ];
    }

    /** @test */
    public function korisnikMozeDaSeRegistruje()
    {
        $response = $this->post($this->url, $this->requestData);
        $response->assertOK();
        $this->assertCount(1, Korisnik::all());
    }

    /** @test */
    public function nazivJeObaveznoPolje()
    {
        $this->requestData['naziv'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());
        TestUtils::assertFieldIsRequired('naziv', $getResponse);
    }

    /** @test */
    public function ulicaIBrojJeObaveznoPolje()
    {
        $this->requestData['ulica_i_broj'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());
        TestUtils::assertFieldIsRequired('ulica i broj', $getResponse);
    }

    /** @test */
    public function gradJeObaveznoPolje()
    {
        $this->requestData['grad'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());
        TestUtils::assertFieldIsRequired('grad', $getResponse);
    }

    /** @test */
    public function passwordJeObaveznoPolje()
    {
        $this->requestData['password'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());
        TestUtils::assertFieldIsRequired('password', $getResponse);
    }

    /** @test */
    public function passwordConfirmationMoraDaBudeJednakoPassword()
    {
        $this->requestData['password_confirmation'] = '';

        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        $this->assertCount(0, Korisnik::all());

        TestUtils::assertFieldIsConfirmed('password', $getResponse);
    }

    /** @test */
    public function probniPeriodJeGodinuDana()
    {
        $this->post($this->url, $this->requestData);

        $korisnik = Korisnik::first();
        $probniPeriod = $korisnik->created_at->diffInYears(
            $korisnik->validan_do
        );

        $this->assertEquals(1, $probniPeriod);
    }

    /** @test */
    public function emailJeObaveznoPolje()
    {
        $this->requestData['email'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());

        TestUtils::assertFieldIsRequired('email', $getResponse);
    }

    /** @test */
    public function emailMoraBitiJedinstven()
    {
        $this->post($this->url, $this->requestData);
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        TestUtils::assertFieldIsUnique('email', $getResponse);
        $this->assertCount(1, Korisnik::all());
    }

    /** @test */
    public function emailMoraBitiValidan()
    {
        $getResponse = function () {
            $this->requestData['email'] = 'nevalidan format email-a';
            return $this->post($this->url, $this->requestData);
        };

        TestUtils::assertFieldIsEmail('email', $getResponse);
        $this->assertCount(0, Korisnik::all());
    }

    /** @test */
    public function telefonJeObaveznoPolje()
    {
        $this->requestData['telefon'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->assertCount(0, Korisnik::all());

        TestUtils::assertFieldIsRequired('telefon', $getResponse);
    }

    /** @test */
    public function detaljiKorisnikaSuKreiraniPrilikomRegistracije()
    {
        $response = $this->post($this->url, $this->requestData);

        $response->assertStatus(200);
        $this->assertCount(1, DetaljiKorisnika::all());
        $this->assertEquals(
            Korisnik::first()->id,
            DetaljiKorisnika::first()->id_korisnika
        );
    }

    /** @test */
    public function podrazumevanaLokacijaSkoleJeKreiranaPrilikomRegistracije()
    {
        $response = $this->post($this->url, $this->requestData);

        $response->assertStatus(200);
        $this->assertCount(1, LokacijaSkole::all());
        $this->assertEquals(
            Korisnik::first()->id,
            LokacijaSkole::first()->id_korisnika
        );
    }

    private function assertFieldIsRequired($polje, $getResponseCallback)
    {
        $response = $getResponseCallback();

        $response->assertStatus(400);

        $responseJson = $response->decodeResponseJson();
        $this->assertCount(1, $responseJson['errors']);

        $this->assertEquals(
            $this->getErrorMessage(Lang::get('validation.required'), $polje),
            $responseJson['errors'][0]
        );
    }
}
