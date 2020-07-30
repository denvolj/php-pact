<?php

namespace Pact;

use Pact\Http\Client as HttpClient;
use Pact\Http\ClientInterface;
use Pact\Http\Request;
use Pact\Service\MessageService;
use Pact\Service\ServiceInterface;

class PactClientBase 
{
    /** @var string default base URL for API */
    const DEFAULT_API_BASE = "https://api.pact.im/p1/";

    /** @var array configuration for current client */
    private $config = [];

    private $http_client;

    private $services = [];

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
        $this->http_client = new HttpClient();
        $this->initServices();
    }

    public function __get($service)
    {
        if (array_key_exists($service, $this->services)) {
            return $this->services[$service];
        }

        return null;
    }

    /**
     * Make request to pact API
     * @param string relative path to API endpoint
     * @param Request request data
     */
    public function request(string $urn, Request $request)
    {
        $request->setHeader('X-Private-Api-Token', $this->api_token);
        $url = static::DEFAULT_API_BASE . $urn;
        $response = $this->http_client->request($url, $request);

        return $response;
    }

    private function initServices()
    {
        $this->services['messages'] = new MessageService($this);
    }
}