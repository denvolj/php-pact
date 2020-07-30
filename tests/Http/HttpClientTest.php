<?php

namespace Pact\Tests\HttpClient;

use Pact\Http\Client as HttpClient;
use Pact\Http\ClientInterface;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    /**
     * Test that we can successfully create http client
     */
    public function testClientCreationSuccessful()
    {
        $client = HttpClient::getClient();
        $this->assertNotEmpty($client);    // created successfull
    }

    /**
     * Check requirements of client: client must implement interface
     */
    public function testClientImplementingInterface()
    {
        $client = HttpClient::getClient();
        
        $this->assertInstanceOf(ClientInterface::class, $client);
    }
}
