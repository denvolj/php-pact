<?php

namespace Pact\Service;

interface ServiceInterface
{
    /**
     * Returns formatted route with pasted parameters
     * 
     * @param mixed substrings to insert in route template
     * @return string 
     */
    public static function getRoute(...$params);

    /**
     * Preparing request to the service and execute
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     * @return mixed result
     */
    public function request(string $method, $uri, array $headers = [], $body = null);
}
