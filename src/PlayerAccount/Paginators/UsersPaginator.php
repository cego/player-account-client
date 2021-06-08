<?php

namespace Cego\PlayerAccount\Paginators;

use Illuminate\Support\Collection;
use Cego\PlayerAccount\PlayerAccount;
use Cego\PlayerAccount\Exceptions\NoSuchPageException;
use Cego\ServiceClientBase\Exceptions\ServiceRequestFailedException;

class UsersPaginator extends Paginator
{
    protected array $queryParameters;
    protected array $requestOptions;
    protected PlayerAccount $client;

    /**
     * UsersPaginator constructor.
     *
     * @param Collection $data
     * @param PlayerAccount $client
     * @param array $queryParameters
     * @param array $requestOptions
     */
    public function __construct(Collection $data, PlayerAccount $client, array $queryParameters, array $requestOptions)
    {
        parent::__construct($data);

        $this->client = $client;
        $this->queryParameters = $queryParameters;
        $this->requestOptions = $requestOptions;
    }

    /**
     * Returns a paginator instance for a specific page
     *
     * @param int $page
     *
     * @return UsersPaginator
     *
     * @throws NoSuchPageException
     * @throws ServiceRequestFailedException
     */
    public function getPage(int $page): UsersPaginator
    {
        if ($page < 1 || $this->getLastPageNumber() < $page) {
            throw new NoSuchPageException($this, $page);
        }

        return $this->client->users(
            explode(',', $this->queryParameters['fields']),
            $page,
            $this->queryParameters['perPage'] ?? null,
            $this->requestOptions,
        );
    }
}
