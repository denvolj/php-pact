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
     * @param array values to insert in route template
     * @param array optional url parameters
     * @return string 
     */
    public function getRoute(array $params, array $query): string
    {
        $this->validateRouteParams($params);
        $query = http_build_query($query);
        if (strlen($query)) {
            $query = '?' . $query;
        }
        return UrlFormatter::format(static::$routeTemplate, $params) . $query;
    }

    /**
     * @param array Route parameters validation method
     * @throws InvalidArgumentException
     */
    protected function validateRouteParams($params)
    {}

    /**
     * Preparing request
     * 
     * @param string HTTP method name 
     * @param array Route parameters that will be inserted in template
     * @param array Additional uri query
     * @param array HTTP Headers
     * @param string|resource|StreamInterface|null Request body
     */
    public function request(string $method, array $routeParams=[], array $query=[], array $headers=[], $body=null)
    {
        $uri = $this->getRoute($routeParams, $query);
        $response = $this->client->request($method, $uri, $headers, $body);
        $statusCode = $response->getStatusCode();

        if (200 >= $statusCode && $statusCode < 300) {
            return json_decode($response->getBody());
        }
        throw new ApiCallException('Api returned HTTP non-OK status: ' . $statusCode);
    }
}
