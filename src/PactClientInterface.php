<?php

namespace Pact;

use Pact\Http\Response;

interface PactClientInterface
{
    public function request(string $method, string $uri, Request $request);
}
