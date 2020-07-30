<?php

namespace Pact\Http;

use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Response as BaseResponse;

class Response extends BaseResponse implements ResponseInterface
{
    public function isOk()
    {
        $status = $this->getStatusCode();
        return (200 <= $status && $status < 300);
    }
}
