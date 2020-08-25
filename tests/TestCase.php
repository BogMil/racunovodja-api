<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getValidJwt()
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

        $response = $this->post('api/auth/prijava', [
            'email' => 'email@adresa.com',
            'password' => 'lozinka',
        ]);

        $responseJson = $response->decodeResponseJson();
        return $responseJson['jwt'];
    }

    public function radiZaAuthentikovanogKorisnika($url, array $data = [])
    {
        $this->withHeader('Authorization', "Bearer {$this->getValidJwt()}");
        $response = $this->post($url, $data);
        $response->assertOk();
    }

    public function _401AkoKorisnikNijePrijavljen($url, array $data = [])
    {
        $response = $this->post($url, $data);
        $response->assertStatus(401);
    }
}
