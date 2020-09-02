<?php

namespace Tests;

use Exception;
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

    public function radiZaAuthentikovanogKorisnika(
        $url,
        $method = "post",
        array $data = []
    ) {
        $this->withJwt();
        $response = $this->getResponse($url, $method, $data);
        $response->assertOk();
    }

    public function _401AkoKorisnikNijePrijavljen(
        $url,
        $method = "post",
        array $data = []
    ) {
        $response = $this->getResponse($url, $method, $data);
        $response->assertStatus(401);
    }

    private function getResponse($url, $method = "post", array $data = [])
    {
        switch ($method) {
            case "get":
                return $this->get($url, $data);
            case "post":
                return $this->post($url, $data);

            default:
                throw new Exception("Unkown http method");
        }
    }

    public function withJwt()
    {
        $this->withHeader('Authorization', "Bearer {$this->getValidJwt()}");
    }

    public function withoutJwt()
    {
        $this->withHeader('Authorization', "");
    }
}
