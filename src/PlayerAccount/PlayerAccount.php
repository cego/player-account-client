<?php

namespace Cego\PlayerAccount;

use Cego\PlayerAccount\Enumerations\Endpoints;
use Cego\ServiceClientBase\AbstractServiceClient;
use Cego\ServiceClientBase\RequestDrivers\Response;
use Cego\ServiceClientBase\Exceptions\ServiceRequestFailedException;

/**
 * Class PlayerAccount
 *
 * Used by applications to interact with the Player Account service
 */
class PlayerAccount extends AbstractServiceClient
{
    /**
     * Trigger an incident for a player
     *
     * @param int $userId
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function incident(int $userId, array $payload): Response
    {
        $endpoint = sprintf(Endpoints::INCIDENT, $userId);

        return $this->postRequest($endpoint, $payload);
    }

    /**
     * Updates a player's attributes
     *
     * @param int $userId
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function update(int $userId, array $payload): Response
    {
        $endpoint = sprintf(Endpoints::UPDATE, $userId);

        return $this->putRequest($endpoint, $payload);
    }

    /**
     * Adds a flag to a player
     *
     * @param int $userId
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function addFlag(int $userId, array $payload): Response
    {
        $endpoint = sprintf(Endpoints::ADD_FLAG, $userId);

        return $this->postRequest($endpoint, $payload);
    }

    /**
     * Removes a flag from a player
     *
     * @param int $userId
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function removeFlag(int $userId, array $payload): Response
    {
        $endpoint = sprintf(Endpoints::REMOVE_FLAG, $userId);

        return $this->postRequest($endpoint, $payload);
    }
}
