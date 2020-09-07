<?php

namespace Tests\Feature\Zaposleni;

use App\Constants\DataValidationErrorMessages;
use App\Korisnik;
use App\Opstina;
use App\Zaposleni;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/zaposleni';
    private $createData;
    private $updateData;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function brisanjeZaposlenogRadi()
    {
        $this->withJwt();
        $this->kreirajPrvogKorisnikaIZaposlenog();

        $this->assertCount(1, Zaposleni::all());
        $this->delete($this->url . '/1');
        $this->assertCount(0, Zaposleni::all());
    }

    //  /** @test */
    //  public function nijeMoguceAzuriranjeTudjegZaposlenog()
    //  {
    //      $this->kreirajPrvogKorisnikaIZaposlenog();
    //      $this->kreirajDrugogKorisnikaIZaposlenog();

    //      $this->updateData['id'] = 2;
    //      $this->withJwt();
    //      $response = $this->put($this->url . "/2", $this->updateData);
    //      $response->assertStatus(400);

    //      $this->assertArrayHasKey('errors', $response->decodeResponseJson());
    //      $this->assertArrayHasKey(
    //          'id',
    //          $response->decodeResponseJson()['errors']
    //      );

    //      $this->assertContains(
    //          DataValidationErrorMessages::neovlasceniPristup,
    //          $response->decodeResponseJson()['errors']['id']
    //      );
    //  }

    private function kreirajPrvogKorisnikaIZaposlenog()
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

        Zaposleni::create([
            'ime' => 'Petar',
            'prezime' => 'Peric',
            'bankovni_racun' => '123456789',
            'email' => 'email@adresa.com',
            'sifra' => '2345',
            'jmbg' => '1231231231231',
            'id_opstine' => '1',
            'id' => '1',
            'id_korisnika' => '1',
        ]);
    }
}
