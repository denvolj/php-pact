<?php

namespace Pact\Service;

use Pact\HttpClient\Request;
use Pact\HttpClient\Response;
use Pact\PactClientInterface;

class AbstractService
{
    /** @var PactClientInterface */
    private $client = null;

    /**
     * @param PactClientInterface
     */
    public function __construct($client)
    {
        $this->client = $client;
    }
    
    /**
     * @param string HTTP method
     * @param string relative path to service
     * @param array query parameters
     * @param mixed request content
     * @return Response
     */
    protected function request(string $method, string $urn, array $query = [], $content = null)
    {
        $request = new Request();
        $request->setMethod($method);
        $request->setQueries($query);
        $request->setContent($content);
        return $this->client->request($urn, $request);
    }

    protected static function buildPath($path, ...$ids)
    {
        foreach ($ids as $id) {
            if (null === $id || '' === trim($id)) {
                $msg = 'The resource ID cannot be null or whitespace.';

                throw new \InvalidArgumentException($msg);
            }
        }

        return sprintf($path, ...array_map('urlencode', $ids));
    }
}