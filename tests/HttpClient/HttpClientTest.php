<?php

namespace Pact\Tests\HttpClient;

use Pact\HttpClient\HttpClient;
use Pact\HttpClient\HttpMethods;
use Pact\HttpClient\Request;
use Pact\HttpClient\Response;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    private $request;

    /** @var HttpClient */
    private $http_client;
    
    protected function setUp(): void
    {
        $this->request = new Request();
        $this->http_client = new HttpClient();
    }

    protected function tearDown(): void
    {
        unset($this->request);
        unset($this->http_client);
    }

    public function testNormalRequestShouldReturnResponseObject()
    {
        $response = $this->http_client->request('http://127.0.0.1/', $this->request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEquals(0, $response->getStatusCode());
    }

    public function testGetAndHeadRequestWithBodyShouldThrowInvalidArgumentException()
    {
        $this->expectErrorMessage('GET and HEAD requests must be without body.');

        $this->request->setMethod(HttpMethods::GET);
        $this->request->setContent(['key'=>'value']);

        $response = $this->http_client->request('http://127.0.0.1/', $this->request);

        $this->request->setMethod(HttpMethods::HEAD);
        $response = $this->http_client->request('http://127.0.0.1/', $this->request);
        
        $this->expectError(\InvalidArgumentException::class);
    }
}