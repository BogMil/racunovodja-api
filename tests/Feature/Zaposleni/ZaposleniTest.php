<?php

namespace Tests\Feature\Zaposleni;

use App\Opstina;
use App\Zaposleni;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ZaposleniTest extends TestCase
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
    public function VracaStatus_401AkoKorisnikNijePrijavljen()
    {
        parent::_401AkoKorisnikNijePrijavljen($this->url, "get");
    }

    /** @test */
    public function radiZaAutentifikovanogKorisnika()
    {
        parent::radiZaAuthentikovanogKorisnika($this->url, "get");
    }

    /** @test */
    public function getVracaListuZaposlenih()
    {
        $this->setIdOpstine();
        $this->withJwt();
        $this->post($this->url, $this->requestData);
        $response = $this->get($this->url, $this->requestData);
        $this->assertIsArray($response->decodeResponseJson());
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
