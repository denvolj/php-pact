<?php

namespace Pact\HttpClient;

use Buzz\Client\Curl as BaseClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Pact\HttpClient\ClientInterface;

class Client extends BaseClient implements ClientInterface
{
    public static function getClient()
    {
        $psr17Factory = new Psr17Factory();
        return new static($psr17Factory);
    }
}
