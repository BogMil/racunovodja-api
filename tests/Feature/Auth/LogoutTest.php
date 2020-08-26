<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\TestCaseHelper;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    private $url = 'api/auth/logout';

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
    public function nakonLogoutaTokenJeNevalidan()
    {
        $this->withJwt();
        $this->post($this->url);
        $request = $this->post($this->url);
        $request->assertStatus(401);
    }
}
