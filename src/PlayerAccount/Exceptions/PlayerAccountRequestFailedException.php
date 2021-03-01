<?php

namespace Cego\PlayerAccount\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\Client\Response;

/**
 * Class PlayerAccountRequestFailedException
 */
class PlayerAccountRequestFailedException extends Exception
{
    /**
     * PlayerAccountRequestFailedException constructor.
     *
     * @param Response $response
     * @param Throwable|null $previous
     */
    public function __construct(Response $response, Throwable $previous = null)
    {
        $message = sprintf('Player Account Service [%s]: %s', $response->status(), $response->body());

        parent::__construct($message, 500, $previous);
    }
}
