<?php

namespace Pact\Http;

use Buzz\Client\Curl as BaseClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Pact\Http\ClientInterface;

class Client extends BaseClient implements ClientInterface
{
    public static function getClient()
    {
        $psr17Factory = new Psr17Factory();
        return new static($psr17Factory);
    }
}
