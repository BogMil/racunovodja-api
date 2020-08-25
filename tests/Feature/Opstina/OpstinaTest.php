<?php

namespace Tests\Feature\Opstina;

use App\Opstina;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

const BROJ_OPSTINA = 196;

class OpstinaTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    private $url = 'api/opstina';
    private $requestData = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function NeMozeDaseKreiraNovaOpstina()
    {
        $response = $this->post($this->url);
        $response->assertStatus(404);
        $this->assertCount(BROJ_OPSTINA, Opstina::all());
    }
}
