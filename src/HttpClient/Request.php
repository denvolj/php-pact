<?php

namespace Pact\HttpClient;

class Request
{
    /**
     * @var array http headers storage
     */
    private array $headers = [];

    /**
     * @var string http method for request
     */
    private string $method = 'GET';

    /**
     * @var array query parameters
     */
    private $query = [];

    /**
     * @var mixed content
     */
    private $content = null;

    /**
     * Default curlopts
     * @var array
     */
    private array $opts = [];

    public function setMethod($new_method) 
    {
        $this->method = $new_method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param array new queries for request
     */
    public function setQueries(array $query)
    {
        $this->query = $query;
    }

    /**
     * @param mixed
     * @param mixed
     */
    public function setQuery($key, $value)
    {
        $this->query[$key] = $value;
    }

    /**
     * @param mixed
     * @return mixed
     */
    public function getQuery($key)
    {
        return $this->query[$key];
    }

    /**
     * @param int option key
     * @param mixed option value
     */
    public function setCurlOpt(int $optkey, $optvalue)
    {
        $this->opts[$optkey] = $optvalue;
    }

    /**
     * @return array
     */
    public function getCurlOpts()
    {
        return $this->opts;
    }

    public function setContent($new_content)
    {
        return $this->content = $new_content;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set header value
     * @param string header name
     * @param mixed header value
     * @return Request for chaining
     */
    public function setHeader(string $name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get header value
     * @param string header name
     * @return mixed
     */
    public function getHeader(string $name)
    {
        if (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
        return NULL;
    }

    public function generateHeaders()
    {
        $result = [];
        foreach($this->headers as $key=>$value) {
            $result[] = "{$key}: {$value}";
        }
        return $result;
    }

    public function generateQuery()
    {
        return http_build_query($this->query);
    }
}