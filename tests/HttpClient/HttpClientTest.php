<?php

namespace Pact\Tests\HttpClient;

use Pact\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    /**
     * Test that we can successfully create http client
     * with defined interface
     */
    public function testClientCreationSuccess()
    {
        new HttpClient([]);
        $this->assertTrue(true);    // test done alredy
    }
}
