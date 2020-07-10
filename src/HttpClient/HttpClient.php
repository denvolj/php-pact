<?php

namespace Pact\HttpClient;

use Pact\HttpClient\HttpMethods;
use Pact\HttpClient\Response;

class HttpClient
{
    /**
     * cURL handle 
     * @var resource
     */
    private $curlHandle = null;

    public function __construct()
    {
        $this->curlHandle = curl_init();
    }

    public function __destruct()
    {
        $this->closeCurlHandle();
    }

    /**
     * @var string url
     * @return Response
     */
    public function request(string $url, Request $request)
    {
        $opts = $request->getCurlOpts();
        if (0 === strcasecmp(HttpMethods::GET, $request->getMethod())
        || 0 === strcasecmp(HttpMethods::HEAD, $request->getMethod())) {
            $opts[CURLOPT_CUSTOMREQUEST] = null;
            if ($request->getContent() !== null) {
                throw new \InvalidArgumentException('GET and HEAD requests must be without body.');
            }
        } else {
            $opts[CURLOPT_CUSTOMREQUEST] = $request->getMethod();
            $content = $request->getContent();

            if (is_array($content) && array_key_exists('file', $content)) {
                $content['file'] = curl_file_create($content['file']);
            }
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $content;
        }
        $query = $request->generateQuery();
        if (strlen($query) > 0) {
            $query = '?' . $query;
        }
        $opts[CURLOPT_URL] = $url . $query;
        $opts[CURLOPT_HTTPHEADER] = $request->generateHeaders();
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_VERBOSE] = true;

        return $this->exec($opts);
    }
    
    /**
     * @param array
     * @return Response
     */
    private function exec($opts)
    {
        $response = new Response();
        $headerCallback = function ($curl, $header_line) use (&$response) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (false === strpos($header_line, ':')) {
                return strlen($header_line);
            }
            list($key, $value) = explode(':', trim($header_line), 2);
            $response->setHeader(trim($key), trim($value));

            return strlen($header_line);
        };

        $opts[CURLOPT_HEADERFUNCTION] = $headerCallback;

        $this->initCurlHandle();
        curl_setopt_array($this->curlHandle, $opts);
        $response_body = curl_exec($this->curlHandle);
        $response->setContent($response_body);
        $response->setStatusCode(curl_getinfo($this->curlHandle, \CURLINFO_HTTP_CODE));
        $this->closeCurlHandle();
        return $response;
    }

    private function initCurlHandle()
    {
        $this->closeCurlHandle();
        $this->curlHandle = curl_init();
    }

    private function closeCurlHandle()
    {
        if ($this->curlHandle === null) {
            return;
        }
        curl_close($this->curlHandle);
        $this->curlHandle = null;
    }
}