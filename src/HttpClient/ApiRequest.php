<?php

namespace Pact\HttpClient;

class ApiRequest
{
    /** @var array Header storage */
    private $headers = [];

    /** @var string Content for requests with body */
    private $content = "";

    /** @var array Key-Value storage for query string */
    private $query = [];

    /** @var string HTTP method name */
    private $method;

    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * Set header value
     * @param string header name
     * @param mixed header value
     * @return Request for chaining
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get header value
     * @param string header name
     * @return mixed
     */
    public function getHeader($name)
    {
        if(array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return NULL;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setContent($new_content)
    {
        $this->content = $new_content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setQuery($key, $value)
    {
        $this->query[$key] = $value;
    }

    public function getQuery($key)
    {
        if(array_key_exists($key, $this->query)) {
            return $this->query[$key];
        }
        return NULL;
    }

    public function buildQuery()
    {
        return http_build_query($this->query);
    }
}