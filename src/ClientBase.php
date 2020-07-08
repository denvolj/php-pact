<?php

namespace Pact;

use Pact\HttpClient\HttpClient;

class PactClientBase 
{
    /** @var string default base URL for API */
    const DEFAULT_API_BASE = "https://api.pact.im/";

    /** @var array configuration for current client */
    private $config = [];

    /**
     * Задаёт начальную конфигурацию
     */
    private function initConfig($new_config) 
    {
        $this->config = array_merge_recursive([
            'api_token' => '',
            'api_base_url' => static::DEFAULT_API_BASE
        ], $new_config);
    }

    /**
     * Инициализирует клиент
     */
    public function __construct($api_token)
    {
        $this->initConfig([
            "api_token" => $api_token
        ]);
    }

    /** @return string Сокращение для получения токена из конфига */
    public function getApiToken()
    {
        return $this->config['api_token'];
    }

    public function getApiUrl()
    {
        return $this->config['api_base_url'];
    }

    /** 
     * 
     */
    public function makeRequest($url, $request)
    {
        $request->setHeader('X-Private-Api-Token', $this->getApiToken());
        $http_client = new HttpClient();
        $response = $http_client->execRequest($this->getApiUrl() . $url, $request);
    }
}