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

        $client = new PactClientBase('');
    }
}