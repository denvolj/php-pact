<?php

namespace Pact\Tests\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\Http\Methods;
use Pact\PactClientBase;
use Pact\PactClientInterface;
use Pact\Service\MessageService;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    private $client = null;
    private $messageService = null;
    private $companyId = null;
    private $conversationId = null;

    protected function setUp(): void
    {
        /** @var PactClientInterface */
        $this->client = $this->getMockBuilder(PactClientBase::class)
            ->setConstructorArgs(['top-secret token do not look 0w0'])
            ->getMock();

        $this->messageService = new MessageService($this->client);
    }

    protected function prepareMock(array $query = [])
    {
        $this->conversationId = random_int(1, 500);
        $this->companyId = random_int(1, 500);
        $this->uri = $this->messageService->getRoute([$this->companyId, $this->conversationId], $query);

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
        }
        $this->addToAssertionCount(1);
    }

    public function testSendMessage()
    {
        foreach([null, []] as $attachments) {
            $this->prepareMock();
            $response = $this->messageService->sendMessage(
                $this->companyId,
                $this->conversationId,
                'Message body',
                $attachments
            );
            $this->assertSame('ok', $response->status);
        }
    }

    public function testSendMessageInvalidAttachmentsThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attachment must be integer');
        $this->prepareMock();
        $response = $this->messageService->sendMessage(
            $this->companyId,
            $this->conversationId,
            'Message body',
            [1.5]
        );
        $this->assertSame('ok', $response->status);
    }

    public function testNotValidCompanyIdThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Id of company must be greater or equal than 0');
        $response = $this->messageService->request(
            Methods::GET,
            [-1, 50]
        );
    }

    public function testNotValidTypeCompanyIdThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Id of company must be integer');
        $response = $this->messageService->request(
            Methods::GET,
            [[], 50]
        );
    }

    public function testNotValidConversationIdThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Id of conversation must be greater or equal than 0');
        $response = $this->messageService->request(
            Methods::GET,
            [50, -1]
        );
    }

    public function testNotValidTypeConversationIdThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Id of conversation must be integer');
        $response = $this->messageService->request(
            Methods::GET,
            [50, []]
        );
    }
}
