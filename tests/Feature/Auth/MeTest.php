<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\TestCaseHelper;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/auth/me';

    /** @test */
    public function VracaStatus_401AkoKorisnikNijePrijavljen()
    {
        parent::_401AkoKorisnikNijePrijavljen($this->url);
    }

    /** @test */
    public function radiZaAutentifikovanogKorisnika()
    {
        parent::radiZaAuthentikovanogKorisnika($this->url);
    }

    /** @test */
    public function vracaKorisnikaUFormatu()
    {
        $this->withJwt();

        $response = $this->post($this->url);
        $responseJson = $response->decodeResponseJson();
        $this->assertArrayHasKey('korisnik', $responseJson);
    }

    /** @test */
    public function neVracaPasswordHashKorisnika()
    {
        $this->withJwt();

        $response = $this->post($this->url);
        $responseJson = $response->decodeResponseJson();
        $this->assertArrayNotHasKey('password', $responseJson['korisnik']);
    }

    /** @test */
    public function vracaPravaPristupa()
    {
        $this->withJwt();

        $response = $this->post($this->url);
        $responseJson = $response->decodeResponseJson();
        // dd($responseJson);
        $this->assertArrayHasKey('prava_pristupa', $responseJson['korisnik']);
    }
}
