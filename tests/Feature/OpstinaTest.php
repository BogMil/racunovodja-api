<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

const BROJ_OPSTINA = 196;

class OpstinaTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    private $url = 'api/opstina';
    private $requestData = [];

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
    public function neMozeDaseKreiraNovaOpstina()
    {
        $response = $this->post($this->url);
        $response->assertStatus(405);
    }

    /** @test */
    public function neMozeDaseAzuriraOpstina()
    {
        $response = $this->put($this->url);
        $response->assertStatus(405);

        $response = $this->patch($this->url);
        $response->assertStatus(405);
    }

    /** @test */
    public function vracaListuOpstina()
    {
        $this->withJwt();
        $response = $this->get($this->url);
        $response->assertOk();
    }

    /** @test */
    public function imaTacnoOdredjenBrojOpstina()
    {
        $this->withJwt();
        $response = $this->get($this->url);
        $this->assertCount(BROJ_OPSTINA, $response->decodeResponseJson());
    }
}
