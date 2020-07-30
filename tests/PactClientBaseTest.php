<?php

namespace Pact\Tests;

use PHPUnit\Framework\TestCase;
use Pact\PactClientBase;

class PactClientBaseTest extends TestCase
{
    public function testTokenlessAppThrowInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectErrorMessage('API token can\'t be empty string');

        new PactClientBase('');
    }

    public function testGetExistingServiceShouldBeOk()
    {
        $client = new PactClientBase('super secret, do not look 0w0');

        $client->message;
    }
}
