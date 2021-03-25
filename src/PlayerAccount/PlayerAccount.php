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
     * Payload to be sent with the request
     *
     * @var array
     */
    protected array $payload = [];

    /**
     * Define payload for the request
     *
     * @param array $payload
     *
     * @return PlayerAccount
     */
    public function withPayload(array $payload): self
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Trigger an incident for a player
     *
     * @param int $userId
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function incident(int $userId): Response
    {
        $endpoint = sprintf(Endpoints::INCIDENT, $userId);

        return $this->postRequest($endpoint, $this->payload);
    }

    /**
     * Updates a player's attributes
     *
     * @param int $userId
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function update(int $userId): Response
    {
        $endpoint = sprintf(Endpoints::UPDATE, $userId);

        return $this->putRequest($endpoint, $this->payload);
    }
}
