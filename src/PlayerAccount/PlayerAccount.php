<?php

namespace Cego\PlayerAccount;

use Carbon\Carbon;
use Cego\PlayerAccount\Enumerations\Endpoints;
use Cego\PlayerAccount\Paginators\UsersPaginator;
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
        $payload = array_merge([
            'sent_at' => Carbon::now()->toISOString()
        ], $payload);

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
     * Adds the same flag to a batch of users
     *
     * @param array $userIds
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function batchAddFlag(array $userIds, array $payload): Response
    {
        $payload['user_ids'] = $userIds;

        return $this->postRequest(Endpoints::BATCH_ADD_FLAG, $payload);
    }

    /**
     * Removes the same flag from a batch of users
     *
     * @param array $userIds
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function batchRemoveFlag(array $userIds, array $payload): Response
    {
        $payload['user_ids'] = $userIds;

        return $this->postRequest(Endpoints::BATCH_REMOVE_FLAG, $payload);
    }

    /**
     * Trigger an incident on a batch of players
     *
     * @param array $userIds
     * @param array $payload
     *
     * @return Response
     *
     * @throws ServiceRequestFailedException
     */
    public function batchIncident(array $userIds, array $payload): Response
    {
        $payload = array_merge([
            'user_ids' => $userIds,
            'sent_at'  => Carbon::now()->toISOString()
        ], $payload);

        return $this->postRequest(Endpoints::BATCH_INCIDENT, $payload);
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

    /**
     * Returns a paginator of all users, with only the given fields returned.
     *
     * @param array $fields
     * @param int $page
     * @param int|null $perPage
     * @param array $options
     *
     * @return UsersPaginator
     *
     * @throws ServiceRequestFailedException
     */
    public function users(array $fields, int $page = 1, ?int $perPage = null, array $options = []): UsersPaginator
    {
        $query = [
            'fields' => implode(',', $fields),
            'page'   => $page,
        ];

        if ($perPage) {
            $query['perPage'] = $perPage;
        }

        $response = $this->getRequest(Endpoints::USERS, $query, $options);

        return new UsersPaginator($response->data, $this, $query, $options);
    }

    /**
     * Returns an associative array, with a key for each given field and the users data for that key as value
     *
     * @param int $userId
     * @param string[] $fields
     * @param array $options
     *
     * @return array
     *
     * @throws ServiceRequestFailedException
     */
    public function user(int $userId, array $fields, array $options = []): array
    {
        $query = ['fields' => implode(',', $fields)];
        $endpoint = sprintf(Endpoints::USER, $userId);

        return $this->getRequest($endpoint, $query, $options)->data->only($fields)->toArray();
    }
}
