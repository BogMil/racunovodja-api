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
use Tests\Feature\BaseTest;
use Tests\TestCase;

class MeTest extends BaseTest
{
    use RefreshDatabase;

    private function getErrorMessage($template, $attribute)
    {
        return str_replace(":attribute", $attribute, $template);
    }

    private $url = 'api/auth/me';

    /** @test */
    public function Status_401IfUnauthenticated()
    {
        $this->post($this->url);
        $response = $this->post($this->url);
        $response->assertStatus(401);
    }

    // /** @test */
    // public function returnsCurrentlyAuthenticatedUser()
    // {
    //     $this->post($this->url, $this->requestData);

    //     $this->post($this->url);
    //     $response = $this->post($this->url);
    //     $response->assertOk();

    //     $responseJson = $response->decodeResponseJson();
    // }
}
