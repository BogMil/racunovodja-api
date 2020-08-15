<?php

namespace Tests\Feature\Auth;

use App\Constants\ResponseStatuses;
use App\Constants\Statuses;
use App\Korisnik;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    private function getErrorMessage($template, $attribute)
    {
        return str_replace(":attribute", $attribute, $template);
    }

    private $url = 'api/auth/register';
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
        ];
    }

    /** @test */
    public function korisnikMozeDaSeRegistruje()
    {
        $this->withoutExceptionHandling();
        $response = $this->post($this->url, $this->requestData);
        $response->assertOK();
        $this->assertCount(1, Korisnik::all());
    }

    /** @test */
    public function nazivJeObaveznoPolje()
    {
        $this->requestData['naziv'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };
        $this->poljeJeObavezno('naziv', $getResponse, Korisnik::class);
    }

    /** @test */
    public function ulicaIBrojJeObaveznoPolje()
    {
        $this->requestData['ulica_i_broj'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        $this->poljeJeObavezno('ulica i broj', $getResponse, Korisnik::class);
    }

    /** @test */
    public function gradJeObaveznoPolje()
    {
        $this->requestData['grad'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        $this->poljeJeObavezno('grad', $getResponse, Korisnik::class);
    }

    /** @test */
    public function passwordJeObaveznoPolje()
    {
        $this->requestData['password'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        $this->poljeJeObavezno('password', $getResponse, Korisnik::class);
    }

    /** @test */
    public function passwordConfirmationMoraDaBudeJednakoPassword()
    {
        $this->requestData['password_confirmation'] = '';

        $response = $this->post($this->url, $this->requestData);

        $responseJson = $response->decodeResponseJson();
        $response->assertStatus(400);
        $this->assertCount(0, Korisnik::all());

        $this->assertCount(1, $responseJson['errors']);

        $this->assertEquals(
            $this->getErrorMessage(
                Lang::get('validation.confirmed'),
                'password'
            ),
            $responseJson['errors'][0]
        );
    }

    /** @test */
    public function probniPeriodJeGodinuDana()
    {
        $this->post($this->url, $this->requestData);

        $korisnik = Korisnik::first();
        $probniPeriod = $korisnik->created_at->diffInYears(
            $korisnik->validan_do
        );

        $this->assertEquals(1, $probniPeriod);
    }

    /** @test */
    public function emailJeObaveznoPolje()
    {
        $this->requestData['email'] = '';
        $getResponse = function () {
            return $this->post($this->url, $this->requestData);
        };

        $this->poljeJeObavezno('email', $getResponse, Korisnik::class);
    }

    /** @test */
    public function emailMoraBitiJedinstven()
    {
        $firstResponse = $this->post($this->url, $this->requestData);
        $secondResponse = $this->post($this->url, $this->requestData);

        $responseJson = $secondResponse->decodeResponseJson();
        $secondResponse->assertStatus(400);
        $this->assertCount(1, Korisnik::all());

        $this->assertCount(1, $responseJson['errors']);

        $this->assertEquals(
            $this->getErrorMessage(Lang::get('validation.unique'), 'email'),
            $responseJson['errors'][0]
        );
    }

    /** @test */
    public function emailMoraBitiValidan()
    {
        $this->requestData['email'] = 'nevalidan format email-a';
        $response = $this->post($this->url, $this->requestData);

        $responseJson = $response->decodeResponseJson();
        $response->assertStatus(400);
        $this->assertCount(0, Korisnik::all());

        $this->assertCount(1, $responseJson['errors']);

        $this->assertEquals(
            $this->getErrorMessage(Lang::get('validation.email'), 'email'),
            $responseJson['errors'][0]
        );
    }

    private function poljeJeObavezno($polje, $getResponseCallback, $model)
    {
        $response = $getResponseCallback();

        $responseJson = $response->decodeResponseJson();
        $response->assertStatus(400);
        $this->assertCount(0, call_user_func("{$model}::all"));

        $this->assertCount(1, $responseJson['errors']);

        $this->assertEquals(
            $this->getErrorMessage(Lang::get('validation.required'), $polje),
            $responseJson['errors'][0]
        );
    }
}
