<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Mockery\MockInterface;
use Cego\PlayerAccount\PlayerAccount;
use Cego\PlayerAccount\Enumerations\Endpoints;

class PlayerAccountTest extends TestCase
{
    protected MockInterface $mock;

    protected function setUp(): void
    {
        $this->mock = Mockery::mock(PlayerAccount::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        parent::setUp();
    }

    /** @test */
    public function it_sends_incident_request()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::INCIDENT, 1), ['type' => 'test']);

        // Act
        $this->mock->incident(1, ['type' => 'test']);
    }

    /** @test */
    public function it_sends_update_request()
    {
        // Assert
        $this->mock->shouldReceive('putRequest')
            ->once()
            ->with(sprintf(Endpoints::UPDATE, 1), ['attribute' => 'newValue']);

        // Act
        $this->mock->update(1, ['attribute' => 'newValue']);
    }

    /** @test */
    public function it_sends_add_flag_request()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::ADD_FLAG, 1), ['flag_type' => 'TestFlagType']);

        // Act
        $this->mock->addFlag(1, ['flag_type' => 'TestFlagType']);
    }

    /** @test */
    public function it_sends_remove_flag_request()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::REMOVE_FLAG, 1), ['flag_type' => 'TestFlagType']);

        // Act
        $this->mock->removeFlag(1, ['flag_type' => 'TestFlagType']);
    }
}
