<?php

namespace Pact\Service;

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
    public function getRoute(...$params) 
    {
        return UrlFormatter::format(static::$routeTemplate, ...$params);
    }

    /**
     * Preparing request to the service and execute
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     */
    public function request(string $method, $uri, array $headers = [], $body = null)
    {
        $response = $this->client->request($method, $uri, $headers, $body);

        if ($response->isOK()) {
            return json_decode($response->getBody())->data;
        }
    }
}
