<?php

namespace Cego\PlayerAccount;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Cego\RequestInsurance\Models\RequestInsurance;
use Cego\PlayerAccount\Exceptions\PlayerAccountRequestFailedException;

class PlayerAccount
{
    const INCIDENT_ENDPOINT = 'api/v1/user/%s/incident';

    const ENVIRONMENTS = [
        'testing',
        'local',
        'stage',
        'production',
    ];

    const SITE_URLS = [
        'spilnu'  => 'spilnu.dk',
        'lyckost' => 'lyckost.se',
    ];

    protected array $headers = [
        'Content-type'  => 'application/json',
        'Accept'        => 'application/json',
    ];

    protected string $baseUrl;
    protected bool $useRequestInsurance = false;

    /**
     * Create new instance of Player Account client
     *
     * @param string $site
     * @param string $environment
     *
     * @return static
     *
     * @throws Exception
     */
    public static function create(string $site, string $environment): self
    {
        // Make sure that the given site and environment are supported
        $site = strtolower($site);
        $environment = strtolower($environment);

        if ( ! isset(static::SITE_URLS[$site])) {
            throw new Exception(sprintf('%s: Unknown site "%s"', __METHOD__, $site), 500);
        }

        if ( ! in_array($environment, static::ENVIRONMENTS)) {
            throw new Exception(sprintf('%s: Unknown environment "%s"', __METHOD__, $environment), 500);
        }

        $playerAccount = new static();

        // Use specific parameters when testing locally
        if ($environment === 'local') {
            $playerAccount->baseUrl = 'http://player-account_api_1';
            $playerAccount->headers['Remote-User'] = sprintf('%s-player-account-client-dev', $site);

            return $playerAccount;
        }

        // Stage and production setup
        $playerAccount->baseUrl = sprintf('https://player-account-%s.%s', $environment, static::SITE_URLS[$site]);

        return $playerAccount;
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
     * @throws PlayerAccountRequestFailedException
     */
    public function incident(int $userId, string $incident)
    {
        $payload = ['type' => $incident];
        $endpoint = sprintf(static::INCIDENT_ENDPOINT, $userId);

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
     * @throws PlayerAccountRequestFailedException
     */
    protected function post(string $endpoint, array $payload)
    {
        if ($this->useRequestInsurance) {
            return $this->makeRequestInsurance('post', $endpoint, $payload);
        } else {
            return $this->makeRequest('post', $endpoint, $payload);
        }
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
     * Makes a Request Insurance to the service
     *
     * @param string $method
     * @param string $endpoint
     * @param array $payload
     *
     * @return RequestInsurance
     */
    protected function makeRequestInsurance(string $method, string $endpoint, array $payload = []): RequestInsurance
    {
        return RequestInsurance::create([
            'url'     => $this->getFullEndpointUrl($endpoint),
            'method'  => $method,
            'payload' => json_encode($payload),
            'headers' => json_encode($this->headers),
        ]);
    }
}
