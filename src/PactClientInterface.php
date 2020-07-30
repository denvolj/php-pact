<?php

namespace Pact;

use Pact\Http\Response;

interface PactClientInterface
{
    /**
     * Preparing request to the service and execute
     * 
     * @param string HTTP method name 
     * @param string URI to endpoint of service
     * @param array HTTP headers
     * @param mixed body of request
     * @return Response
     */
    public function request(string $method, $uri, array $headers = [], $body = null);
}
