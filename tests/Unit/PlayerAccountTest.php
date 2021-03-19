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
        $this->mock->incident(1, 'test');
    }

    /** @test */
    public function incident_with_admin_user()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::INCIDENT, 1), ['type' => 'test', 'admin_user_id' => 2]);

        // Act
        $this->mock->incident(1, 'test', 2);
    }

    /** @test */
    public function incident_with_admin_user_and_reason()
    {
        // Assert
        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::INCIDENT, 1), ['type' => 'test', 'admin_user_id' => 2, 'reason' => 'test reason']);

        // Act
        $this->mock->incident(1, 'test', 2, 'test reason');
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

    /** @test */
    public function update_with_admin_user()
    {
        // Assert
        $this->mock->shouldReceive('putRequest')
            ->once()
            ->with(sprintf(Endpoints::UPDATE, 1), ['attribute' => 'newValue', 'admin_user_id' => 2]);

        // Act
        $this->mock->update(1, ['attribute' => 'newValue'], 2);
    }
}
