<?php

namespace Pact;

use Pact\HttpClient\Request;

interface PactClientInterface
{
    public function request(string $method, string $uri, Request $request);
}
