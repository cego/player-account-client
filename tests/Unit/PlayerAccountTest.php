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
    public function incident()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::INCIDENT, 1), ['type' => 'test']);

        // Act
        $this->mock->incident(1, ['type' => 'test']);
    }

    /** @test */
    public function update_without_admin_user()
    {
        // Assert
        $this->mock->shouldReceive('putRequest')
            ->once()
            ->with(sprintf(Endpoints::UPDATE, 1), ['attribute' => 'newValue']);

        // Act
        $this->mock->update(1, ['attribute' => 'newValue']);
    }
}
