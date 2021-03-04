<?php

namespace Cego\PlayerAccount;

use InvalidArgumentException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Cego\PlayerAccount\Enumerations\Endpoints;
use Cego\RequestInsurance\Models\RequestInsurance;
use Illuminate\Contracts\Container\BindingResolutionException;
use Cego\PlayerAccount\Exceptions\PlayerAccountRequestFailedException;

/**
 * Class PlayerAccount
 *
 * Used by applications to interact with the Player Account service
 */
class PlayerAccount
{
    /**
     * HTTP-headers to be sent to the Player Account service
     *
     * @var string[]
     */
    protected array $headers = [
        'Content-type'  => 'application/json',
        'Accept'        => 'application/json',
        'Remote-User'   => 'player-account-client-dev',
    ];

    /**
     * The base URL of the Player Account service
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * Specifies if Request Insurance should be used to handle requests to the Player Account service
     *
     * @var bool
     */
    protected bool $useRequestInsurance = false;

    /**
     * Protected PlayerAccount constructor, to enforce use of custom create method
     *
     * @param string $baseUrl
     */
    final protected function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Create new instance of Player Account client
     *
     * @param string $baseUrl
     *
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function create(string $baseUrl): self
    {
        return new static($baseUrl);
    }

    /**
     * Set basic auth header
     *
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function auth(string $username, string $password): self
    {
        $auth = sprintf('%s:%s', $username, $password);
        $this->headers['Authorization'] = sprintf('Basic %s', base64_encode($auth));

        return $this;
    }

    /**
     * Specify if Request Insurance should be used
     *
     * @param bool $shouldUseRequestInsurance
     *
     * @return $this
     */
    public function useRequestInsurance(bool $shouldUseRequestInsurance = true): self
    {
        $this->useRequestInsurance = $shouldUseRequestInsurance;

        return $this;
    }

    /**
     * Trigger an incident for a player
     *
     * @param int $userId
     * @param string $incident
     *
     * @return Response|RequestInsurance
     *
     * @throws PlayerAccountRequestFailedException|BindingResolutionException
     */
    public function incident(int $userId, string $incident)
    {
        $payload = ['type' => $incident];
        $endpoint = sprintf(Endpoints::INCIDENT, $userId);

        return $this->post($endpoint, $payload);
    }

    /**
     * Updates a player's attributes
     *
     * @param int $userId
     * @param array $attributes
     * @param int|null $adminUserId
     *
     * @return Response|RequestInsurance
     *
     * @throws PlayerAccountRequestFailedException|BindingResolutionException
     */
    public function update(int $userId, array $attributes, ?int $adminUserId = null)
    {
        $payload = $attributes;

        if($adminUserId !== null) {
            $payload['admin_user_id'] = $adminUserId;
        }

        $endpoint = sprintf(Endpoints::UPDATE, $userId);

        return $this->post($endpoint, $payload);
    }

    /**
     * Gets the full endpoint url
     *
     * @param string $endpoint
     *
     * @return string
     */
    protected function getFullEndpointUrl(string $endpoint): string
    {
        return sprintf('%s/%s', $this->baseUrl, $endpoint);
    }

    /**
     * Make a POST request to the service
     *
     * @param string $endpoint
     * @param array $payload
     *
     * @return Response|RequestInsurance
     *
     * @throws PlayerAccountRequestFailedException|BindingResolutionException
     */
    protected function post(string $endpoint, array $payload)
    {
        if ($this->useRequestInsurance) {
            return $this->makeRequestInsurance('post', $endpoint, $payload);
        }

        return $this->makeRequest('post', $endpoint, $payload);
    }

    /**
     * Makes a request to the service
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     *
     * @return Response
     *
     * @throws PlayerAccountRequestFailedException
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): Response
    {
        $maxTries = env("PLAYER_ACCOUNT_CLIENT_MAXIMUM_NUMBER_OF_RETRIES", 3);
        $try = 0;

        do {
            /** @var Response $response */
            $response = Http::withHeaders($this->headers)
                ->timeout(env("PLAYER_ACCOUNT_CLIENT_TIMEOUT", 1))
                ->$method($this->getFullEndpointUrl($endpoint), $data);

            // Bailout if successful
            if ($response->successful()) {
                return $response;
            }

            // Do not retry client errors
            if ($response->clientError()) {
                throw new PlayerAccountRequestFailedException($response);
            }

            // Wait 1 sec before trying again, if server error
            usleep(env("PLAYER_ACCOUNT_CLIENT_RETRY_DELAY", 1000000));
            $try++;
        } while ($try < $maxTries);

        throw new PlayerAccountRequestFailedException($response);
    }

    /**
     * Makes a Request Insurance to Player Account service
     *
     * @param string $method
     * @param string $endpoint
     * @param array $payload
     *
     * @return RequestInsurance
     *
     * @throws BindingResolutionException
     */
    protected function makeRequestInsurance(string $method, string $endpoint, array $payload = []): RequestInsurance
    {
        // We use the IOC container to make mocking possible
        /** @var RequestInsurance $requestInsurance */
        $requestInsurance = app()->make(RequestInsurance::class);
        $requestInsurance->fill([
            'url'     => $this->getFullEndpointUrl($endpoint),
            'method'  => $method,
            'payload' => json_encode($payload),
            'headers' => json_encode($this->headers),
        ])->save();

        return $requestInsurance;
    }
}
