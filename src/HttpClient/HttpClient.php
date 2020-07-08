<?php

namespace Pact\HttpClient;

use Pact\HttpClient\HttpMethods;

class HttpClient
{
    /** @var resource cURL instance */
    private $curlHandle = null;

    public function __construct()
    {
        $this->curlHandle = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curlHandle);
    }

    /**
     * @param string full url to api 
     * @param Pact\HttpClient\ApiRequest;
     */
    public function execRequest($url, $request)
    {
        $query = $request->buildQuery();
        
        if(count($query) > 0) {
            $url = "{$url}?{$query}";
        }

        curl_setopt($this->curlHandle, CURLOPT_URL, $url);
        curl_setopt($this->curlHandle, CURLOPT_HEADER, $this->headers);
        curl_setopt($this->curlHandle, CURLOPT_CUSTOMREQUEST, $request->getMethod()); 
        curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true); 

        if(HttpMethods::POST && $request->getMethod()) {
            curl_setopt($this->curlHandle, CURLOPT_POST, 1);
            curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $request->getContent());
        }

        curl_exec($this->curlHandle);
    }
}