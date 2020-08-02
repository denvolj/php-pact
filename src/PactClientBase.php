<?php

namespace Pact;

use Pact\Http\ClientInterface;
use Pact\Http\Factory;
use Pact\Service\ServiceFactory;
use Psr\Http\Message\ResponseInterface;

class PactClientBase implements PactClientInterface
{
    /** @var string default base URL for API */
    const DEFAULT_API_BASE = "https://api.pact.im/p1/";

    /** @var array configuration for current client */
    private $config = [];

    /** @var ClientInterface */
    private $http_client;

    /** @var ServiceFactory */
    private $services = null;

    /**
     * @var string Secret token for authentication
     */
    private $api_token;

    /**
     * @param string Secret token used for authentication
     */
    public function __construct(string $api_token)
    {
        if ($api_token === '') {
            throw new \InvalidArgumentException('API token can\'t be empty string');
        }

        $this->api_token = $api_token;
        $this->http_client = Factory::client();
    }

    public function __get($serviceName)
    {
        return $this->services[$serviceName];
    }

    /**
     * Preparing request to the service and execute
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     * @return ResponseInterface
     */
    public function request(string $method, $uri, array $headers = [], $body = null): ResponseInterface
    {
        $url = self::DEFAULT_API_BASE . $uri;
        $headers['X-Private-Api-Token'] = $this->api_token;

        $request = Factory::request($method, $url, $headers, $body);
        $response = $this->http_client->sendRequest($request);

        return $response;
    }
}
