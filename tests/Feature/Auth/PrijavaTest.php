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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Namshi\JOSE\JWT as JOSEJWT;
use stdClass;
use Tests\TestCase;
use Tests\TestUtils;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class PrijavaTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/auth/prijava';
    private $requestData = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->requestData = [
            'email' => 'email',
            'password' => 'lozinka',
        ];
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
    public function zaNeispravneKredencijaleVracaGreskuOPogresnimKredencijalima()
    {
        $this->requestData['password'] = 'nepostojeci';
        $this->requestData['email'] = 'nepostojeci@korisnik.com';
        $response = $this->post($this->url, $this->requestData);

        $responseJson = $response->decodeResponseJson();
        $this->assertArrayHasKey('errors', $responseJson);

        $this->assertEquals(
            ["greska" => "Pogrešni kredencijali"],
            $responseJson['errors']
        );
    }

    /** @test */
    public function zaIspravneKredencijaleVracaJwt()
    {
        $response = $this->responseIspravnogRequesta();

        $response->assertOk();
        $responseJson = $response->decodeResponseJson();
        $this->assertArrayHasKey('jwt', $responseJson);
    }

    /** @test */
    public function jwtSadrziPravaPristupa()
    {
        $response = $this->responseIspravnogRequesta();

        $response->assertOk();
        $responseJson = $response->decodeResponseJson();
        $token = $responseJson['jwt'];
        $tokenPauload = json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1])
                )
            )
        );

        $this->objectHasAttribute('prava_pristupa', $tokenPauload);
    }

    /** @test */
    public function jwtSadrziPravaPristupaZaTacno2DelaAplikacije()
    {
        $response = $this->responseIspravnogRequesta();
        $response->assertOk();
        $responseJson = $response->decodeResponseJson();
        $token = $responseJson['jwt'];
        $tokenPauload = $this->decodeJwtPayload($token);

        $this->assertCount(2, get_object_vars($tokenPauload->prava_pristupa));
    }

    private function responseIspravnogRequesta()
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

        return $this->post($this->url, [
            'email' => 'email@adresa.com',
            'password' => 'lozinka',
        ]);
    }

    private function decodeJwtPayload($token)
    {
        return json_decode(
            base64_decode(
                str_replace(
                    '_',
                    '/',
                    str_replace('-', '+', explode('.', $token)[1])
                )
            )
        );
    }
}
