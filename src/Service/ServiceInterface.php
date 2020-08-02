<?php

namespace Pact\Service;

interface ServiceInterface
{
    /**
     * Returns formatted route with pasted parameters
     * 
     * @param array values to insert in route template
     * @param array optional url parameters
     * @return string 
     */
    public function getRoute($params, $query);

    /**
     * Preparing request to the service and execute
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     * @return mixed result
     */
    public function request(string $method, array $routeParams=[], array $query=[], array $headers=[], $body=null);
}
