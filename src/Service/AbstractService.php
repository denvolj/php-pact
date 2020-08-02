<?php

namespace Pact\Service;

use Pact\Exception\ApiCallException;
use Pact\PactClientInterface;
use Pact\Service\ApiObjectInterface;
use Pact\Service\ServiceInterface;
use Pact\Utils\UrlFormatter;

abstract class AbstractService implements ServiceInterface
{   
    /**
     * @var ApiObjectInterface
     */
    protected static $apiObjectClass = null;

    /**
     * @var string Formatted string contains pattern for route formatting
     * @example "/companies/%s/conversation/%s/
     */
    protected static string $routeTemplate = "";

    /**
     * @var PactClientInterface
     */
    protected $client;

    /**
     * Constructor
     * 
     * @param PactClientInterface
     */
    public function __construct(PactClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Returns formatted route with pasted parameters
     * 
     * @param mixed substrings to insert in route template
     * @return string 
     */
    public static function getRoute(...$params) 
    {
        return UrlFormatter::format(static::$routeTemplate, ...$params);
    }

    /**
     * Preparing request
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     */
    public function request(string $method, $uri, array $headers = [], $body = null)
    {
        $response = $this->client->request($method, $uri, $headers, $body);

        $statusCode = $response->getStatusCode();

        if (200 >= $statusCode && $statusCode < 300) {
            return json_decode($response->getBody());
        }
        throw new ApiCallException('Api returned non-OK status: ' . $statusCode);
    }
}
