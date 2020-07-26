<?php

namespace Pact\HttpClient;

use Psr\Http\Message\RequestInterface;
use Nyholm\Psr7\Request as BaseRequest;

class Request extends BaseRequest implements RequestInterface
{}
