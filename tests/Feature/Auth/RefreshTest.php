<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/auth/refresh';

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
    public function vracaNoviToken()
    {
        $firstToken = $this->getValidJwt();

        $this->withHeader('Authorization', "Bearer {$firstToken}");
        $response = $this->post($this->url);

        $responseJson = $response->decodeResponseJson();

        $this->assertArrayHasKey('jwt', $responseJson);
        $this->assertNotEquals($firstToken, $responseJson['jwt']);
    }

    /** @test */
    public function refresovaniTokenNijeViseValidan()
    {
        $this->withJwt();
        $this->post($this->url);

        $response = $this->post($this->url);

        $response->assertStatus(500);
    }
}
