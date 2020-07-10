<?php

namespace Pact\HttpClient;

class Response 
{
    private $statusCode = NULL;
    private $headers = [];
    private $content = null;

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

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed
     */
    public function setContent($new_content)
    {
        $this->content = $new_content;
    }
    
    /**
     * @param int 
     */
    public function setStatusCode($new_code)
    {
        $this->statusCode = $new_code;
    }

    /**
     * @return int Status code of executed request
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function isOK()
    {
        return $this->statusCode >= 200 && $this->statusCode < 400;
    }
}
