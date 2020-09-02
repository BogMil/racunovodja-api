<?php

namespace Tests\Feature\Zaposleni;

use App\Opstina;
use App\Zaposleni;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/zaposleni';
    private $requestData;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->requestData = [
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email' => 'email@adresa.com',
            'sifra' => '2345',
            'jmbg' => '1231231231231',
            'id_opstine' => '1',
        ];
    }

    /** @test */
    public function zaposleniMozeDaSeKreira()
    {
        $this->withJwt();
        $this->setIdOpstine();
        $response = $this->post($this->url, $this->requestData);

        $response->assertOK();
        $this->assertCount(1, Zaposleni::all());
    }

    /** @test */
    public function idKorisnikaJeSetovano()
    {
        $this->withJwt();
        $this->setIdOpstine();
        $this->post($this->url, $this->requestData);

        $this->assertEquals(1, Zaposleni::first()->id_korisnika);
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
    public function bankovniRacunJeObavezno()
    {
        $this->poljeJeObavezno('bankovni_racun');
    }

    /** @test */
    public function aktivanNijeObaveznoPolje()
    {
        $this->poljeNijeObavezno('aktivan');
    }

    /** @test */
    public function podrazumevanaVrednostZaAktivanJeTrue()
    {
        $this->withJwt();
        $this->setIdOpstine();
        $this->requestData['aktivan'] = '';
        $this->post($this->url, $this->requestData);

        $this->assertEquals(true, Zaposleni::first()->aktivan);
    }

    /** @test */
    public function idOpstineNijeObaveznoPolje()
    {
        $this->poljeNijeObavezno('id_opstine');
    }

    /** @test */
    public function emailNijeObaveznoPolje()
    {
        $this->poljeNijeObavezno('email');
    }

    /** @test */
    public function emailMoraBitiValidan()
    {
        $this->withJwt();
        $getResponse = function () {
            $this->requestData['email'] = 'nevalidan format email-a';
            return $this->post($this->url, $this->requestData);
        };

        TestUtils::assertFieldIsEmail('email', $getResponse);
        $this->assertCount(0, Zaposleni::all());
    }

    /** @test */
    public function jmbgJeJedinstvenUOkviruKorisnika()
    {
        $this->withJwt();
        $this->post($this->url, $this->requestData);
        $this->assertCount(1, Zaposleni::all());

        $this->requestData['sifra'] = '999';
        $response = $this->post($this->url, $this->requestData);

        $this->assertCount(1, Zaposleni::all());
        $responseJson = $response->decodeResponseJson();
        $this->assertArrayHasKey('errors', $responseJson);
        $this->assertArrayHasKey('jmbg', $responseJson['errors']);
        $this->assertContains(
            'Zaposleni sa tim jmbg-om već postoji.',
            $responseJson['errors']['jmbg']
        );
    }

    /** @test */
    public function sifraJeJedinstvenaUOkviruKorisnika()
    {
        $this->withJwt();
        $this->post($this->url, $this->requestData);
        $this->assertCount(1, Zaposleni::all());

        $this->requestData['jmbg'] = '1232343450000';
        $response = $this->post($this->url, $this->requestData);
        $this->assertCount(1, Zaposleni::all());

        $responseJson = $response->decodeResponseJson();
        $this->assertArrayHasKey('errors', $responseJson);
        $this->assertArrayHasKey('sifra', $responseJson['errors']);
        $this->assertContains(
            'Zaposleni sa tom šifrom već postoji.',
            $responseJson['errors']['sifra']
        );
    }

    private function poljeNijeObavezno($nazivPolja)
    {
        $this->withJwt();
        $this->requestData[$nazivPolja] = '';
        $response = $this->post($this->url, $this->requestData);

        $response->assertOK();
        $this->assertCount(1, Zaposleni::all());
    }

    private function poljeJeObavezno($nazivPolja)
    {
        $this->withJwt();
        $this->requestData[$nazivPolja] = '';
        $this->assertCount(0, Zaposleni::all());
        TestUtils::assertFieldIsRequired(
            $nazivPolja,
            $this->getResponseCallback()
        );
    }

    private function getResponseCallback()
    {
        return function () {
            return $this->post($this->url, $this->requestData);
        };
    }

    private function idPrveOpstine()
    {
        $this->withJwt();
        $opstineResponse = $this->get('api/opstina');
        return $opstineResponse->decodeResponseJson()[1]['id'];
    }

    private function setIdOpstine()
    {
        $this->requestData['id_opstine'] = $this->idPrveOpstine();
    }
}
