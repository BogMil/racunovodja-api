<?php

namespace Tests\Feature;

use App\Constants\ResponseStatuses;
use App\Constants\Statuses;
use App\DetaljiKorisnika;
use App\Korisnik;
use App\LokacijaSkole;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\TestUtils;

class BaseTest extends TestCase
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

    public function korisnikMozeDaSeRegistruje()
    {
        $this->post($this->url, $this->requestData);
    }
}
