<?php

namespace Tests\Feature\Zaposleni;

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
    public function zaposleniMozeDaSeAzurira()
    {
        $this->withJwt();
        // $this->setIdOpstine();
        $response = $this->post($this->url, $this->requestData);
        $response = $this->put($this->url, $this->requestData);

        $response->assertOK();
        $this->assertCount(1, Zaposleni::all());
    }
}
