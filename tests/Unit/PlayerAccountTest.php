<?php

namespace Tests\Unit;

use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use Mockery\MockInterface;
use Cego\PlayerAccount\PlayerAccount;
use Cego\PlayerAccount\Enumerations\Endpoints;
use Cego\PlayerAccount\Paginators\UsersPaginator;
use Cego\ServiceClientBase\RequestDrivers\Response;

class PlayerAccountTest extends TestCase
{
    /** @var MockInterface|Mockery\Mock|PlayerAccount */
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
        $this->travelTo(Carbon::now()); // This is needed to lock the Carbon::now() to a fixed value, so the test can pass

        $this->mock->shouldReceive('postRequest')
            ->once()
            ->with(sprintf(Endpoints::INCIDENT, 1), ['type' => 'test', 'sent_at' => Carbon::now()->toISOString()]);

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

    /** @test */
    public function it_sends_users_requests(): void
    {
        // Arrange
        $fields = ['a', 'b', 'c'];
        $page = 2;
        $perPage = 123;

        $expectedQuery = [
            'fields'  => implode(',', $fields),
            'page'    => $page,
            'perPage' => $perPage,
        ];

        // Assert
        $this->mock->shouldReceive('getRequest')
            ->once()
            ->with(Endpoints::USERS, $expectedQuery, [])
            ->andReturn(new Response(0, [], true));

        // Act
        $this->mock->users($fields, $page, $perPage);
    }

    /** @test */
    public function it_can_access_multiple_pages_for_the_users_paginator(): void
    {
        // Arrange
        $fields = ['a', 'b', 'c'];
        $page = 2;
        $perPage = 123;

        // Assert
        $this->mock->shouldReceive('getRequest')
            ->once()
            ->with(Endpoints::USERS, [
                'fields'  => implode(',', $fields),
                'page'    => $page,
                'perPage' => $perPage,
            ], [])
            ->andReturn(new Response(200, [
                'last_page'    => 3,
                'current_page' => $page,
            ], true));

        $this->mock->shouldReceive('getRequest')
            ->once()
            ->with(Endpoints::USERS, [
                'fields'  => implode(',', $fields),
                'page'    => $page + 1,
                'perPage' => $perPage,
            ], [])
            ->andReturn(new Response(200, [
                'last_page'    => 3,
                'current_page' => $page + 1,
            ], true));

        $this->mock->shouldReceive('getRequest')
            ->once()
            ->with(Endpoints::USERS, [
                'fields'  => implode(',', $fields),
                'page'    => $page - 1,
                'perPage' => $perPage,
            ], [])
            ->andReturn(new Response(200, [
                'last_page'    => 3,
                'current_page' => $page - 1,
            ], true));

        // Act
        /** @var UsersPaginator $usersPaginator */
        $usersPaginator = $this->mock->users($fields, $page, $perPage);

        $usersPaginator->getNextPage();
        $usersPaginator->getPreviousPage();
    }

    /** @test */
    public function it_can_return_data_for_a_single_user(): void
    {
        // Arrange
        $fields = ['a', 'b', 'c'];
        $userId = 123;

        // Assert
        $this->mock->shouldReceive('getRequest')
            ->once()
            ->with(sprintf(Endpoints::USER, $userId), ['fields'  => implode(',', $fields)], [])
            ->andReturn(new Response(200, [
                'a' => 'aa',
                'b' => 'bb',
                'c' => 'cc',
            ], true));

        // Act
        $data = $this->mock->user($userId, $fields);
        $this->assertEquals([
            'a' => 'aa',
            'b' => 'bb',
            'c' => 'cc',
        ], $data);
    }
}
