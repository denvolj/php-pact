<?php

namespace Pact\Service;

use Pact\Exception\ApiCallException;
use Pact\PactClientInterface;
use Pact\Service\Validation\ValidationFactory;
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
    protected static string $endpoint = '';

    /**
     * @var PactClientInterface
     */
    protected $client;

    /**
     * @var ValidationFactory
     */
    protected $validator;

    /**
     * Constructor
     * 
     * @param PactClientInterface
     */
    public function __construct(PactClientInterface $client)
    {
        $this->client = $client;
        $this->validator = ValidationFactory::getInstance();
    }

    public function getRouteTemplate()
    {
        return static::$endpoint;
    }

    /**
     * Returns formatted uri with pasted parameters
     * 
     * @param string endpoint template string
     * @param array values to insert in route template
     * @param array optional url parameters
     * @return string 
     */
    public function formatEndpoint(string $endpoint,array $params, array $query): string
    {
        $this->validateRouteParams($params);
        $query = http_build_query($query);
        if (strlen($query)) {
            $query = '?' . $query;
        }
        return UrlFormatter::format($endpoint, $params) . $query;
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
     * @param string endpoint template string
     * @param array Route parameters that will be inserted in template
     * @param string|resource|StreamInterface|null Request body
     * @param array Additional uri query
     * @param array HTTP Headers
     */
    public function request(string $method, string $endpoint, array $endpointParams=[], $body=null, array $query=[], array $headers=[])
    {
        if (is_array($body)) {
            $body = http_build_query($body);
        }

        $uri = $this->formatEndpoint($endpoint, $endpointParams, $query);
        $response = $this->client->request($method, $uri, $headers, $body);
        $statusCode = $response->getStatusCode();

        if (200 <= $statusCode && $statusCode < 300) {
            return json_decode($response->getBody());
        }
        throw new ApiCallException('Api returned HTTP non-OK status: ' . $statusCode);
    }
}
