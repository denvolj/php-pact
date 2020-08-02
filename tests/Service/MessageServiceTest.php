<?php

namespace Pact\Tests\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\Http\Methods;
use Pact\PactClientBase;
use Pact\Service\MessageService;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    private $client = null;
    private $messageService = null;
    private $companyId = null;
    private $conversationId = null;
    private $uri = '';

    protected function setUp(): void
    {
        $this->client = $this->getMockBuilder(PactClientBase::class)
            ->setConstructorArgs(['top-secret token do not look 0w0'])
            ->getMock();

        $this->messageService = new MessageService($this->client);
    }

    protected function prepareMock(array $query = [])
    {
        $this->conversationId = random_int(1, 500);
        $this->companyId = random_int(1, 500);
        $this->uri = MessageService::getRoute([$this->companyId, $this->conversationId], $query);

        // Configure the stub.
        $this->client->expects($this->any())
            ->method('request')
            ->will($this->returnValue(Factory::response(200, [], '{"status":"ok"}')));
    }

    public function testNormalGetMessages()
    {
        $this->prepareMock();
            
        $response = $this->messageService->getMessages(
            $this->companyId,
            $this->conversationId
        );
        $this->assertSame('ok', $response->status);
    }

    public function testValidSortGetMessage()
    {
        foreach (['asc', 'desc'] as $sort) {
            $this->prepareMock(['sort' => $sort]);
            $response = $this->messageService->getMessages(
                $this->companyId,
                $this->conversationId,
                null,
                null,
                $sort
            );
            $this->assertSame('ok', $response->status);
        }
    }

    public function testInvalidSortGetMessageThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Sort parameter must be asc or desc');
        $this->prepareMock(['sort' => 'asdf']);
        $response = $this->messageService->getMessages(
            $this->companyId,
            $this->conversationId,
            null,
            null,
            'asdf'
        );
    }

    public function testInvalidFetchCountThrowsInvalidArgument()
    {
        foreach ([0, 101] as $fetchCount) {
            $this->prepareMock(['per' => $fetchCount]);
            try {
                $response = $this->messageService->getMessages(
                    $this->companyId,
                    $this->conversationId,
                    null,
                    $fetchCount
                );
                $this->fail('Exception not thrown');
            } catch (InvalidArgumentException $e) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function testValidFetchCount()
    {
        for ($fetchCount=1; $fetchCount<101;$fetchCount++) {
            $this->prepareMock(['per' => $fetchCount]);
            $response = $this->messageService->getMessages(
                $this->companyId,
                $this->conversationId,
                null,
                $fetchCount
            );
            $this->assertSame('ok', $response->status);
        }
    }
}
