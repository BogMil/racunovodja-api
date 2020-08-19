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
use Illuminate\Support\Facades\Lang;
use stdClass;
use Tests\TestCase;
use Tests\TestUtils;

class PrijavaTest extends TestCase
{
    use RefreshDatabase;

    private function getErrorMessage($template, $attribute)
    {
        return str_replace(":attribute", $attribute, $template);
    }

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
    public function zaNepostojecegKorisnikaVracaGreskuOPogresnimKredencijalima()
    {
        $this->requestData['password'] = 'asdasd';
        $this->requestData['email'] = 'asdasd@asd.com';
        $request = $this->post($this->url, $this->requestData);

        $responseJson = $request->decodeResponseJson();
        dd($responseJson);
        $this->assertContains('errors', $responseJson);
    }
}
