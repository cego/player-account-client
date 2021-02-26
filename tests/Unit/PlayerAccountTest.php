<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Cego\PlayerAccount\PlayerAccount;
use Cego\PlayerAccount\Enumerations\Endpoints;
use Cego\RequestInsurance\Models\RequestInsurance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlayerAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_incident()
    {
        Http::fake();
        $response = PlayerAccount::create('https://player-account-testing.spilnu.dk')->incident(1, 'test');
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function it_creates_incident_with_request_insurance()
    {
        $baseUrl = 'https://player-account-testing.spilnu.dk';
        $endpointUrl = sprintf(Endpoints::INCIDENT, 1);

        $mock = Mockery::mock(RequestInsurance::class)->makePartial();
        $mock->shouldReceive('setAttribute')->once()->with('url', sprintf('%s/%s', $baseUrl, $endpointUrl));
        $mock->shouldReceive('setAttribute')->once()->with('method', 'post');
        $mock->shouldReceive('setAttribute')->once()->with('payload', json_encode(['type' => 'test']));
        $mock->shouldReceive('save')->once();

        $this->instance(
            RequestInsurance::class,
            $mock
        );

        PlayerAccount::create('https://player-account-testing.spilnu.dk')->useRequestInsurance()->incident(1, 'test');
    }
}
