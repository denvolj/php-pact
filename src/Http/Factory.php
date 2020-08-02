<?php

namespace Pact\Http;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use Buzz\Client\Curl as HttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;

/**
 * Class provides abstract layer for http entities
 * for use in project.
 * So if anyone has need to use another implementations of
 * requests/response/curlclient you can change it here.
 */
class Factory
{
    public static function request($method, $uri, $headers=[], $body=null): RequestInterface
    {
        return new Request($method, $uri, $headers, $body);
    }

    public static function response(int $status = 200, array $headers = [], $body = null): ResponseInterface
    {
        return new Response($status, $headers, $body);
    }

    public static function client(): ClientInterface
    {
        $psrFactory = new Psr17Factory();
        return new HttpClient($psrFactory);
    }
}
