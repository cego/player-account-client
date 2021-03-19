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
     * @param string $incident
     * @param int|null $adminUserId
     * @param string|null $reason
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function incident(int $userId, string $incident, ?int $adminUserId = null, ?string $reason = null): Response
    {
        $payload = ['type' => $incident];

        if ($adminUserId !== null) {
            $payload['admin_user_id'] = $adminUserId;
        }

        if ($reason !== null) {
            $payload['reason'] = $reason;
        }

        $endpoint = sprintf(Endpoints::INCIDENT, $userId);

        return $this->postRequest($endpoint, $payload);
    }

    /**
     * Updates a player's attributes
     *
     * @param int $userId
     * @param array $attributes
     * @param int|null $adminUserId
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function update(int $userId, array $attributes, ?int $adminUserId = null): Response
    {
        $payload = $attributes;

        if ($adminUserId !== null) {
            $payload['admin_user_id'] = $adminUserId;
        }

        $endpoint = sprintf(Endpoints::UPDATE, $userId);

        return $this->putRequest($endpoint, $payload);
    }
}
