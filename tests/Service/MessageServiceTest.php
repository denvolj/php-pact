<?php

namespace Pact\Tests\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\PactClientBase;
use Pact\PactClientInterface;
use Pact\Service\MessageService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class MessageServiceTest extends TestCase
{
    /** @var PactClientInterface|MockObject */
    private $client = null;

    /** @var MessageService */
    private $messageService = null;
    private $companyId = null;
    private $conversationId = null;
    private $url;

    protected function setUp(): void
    {
        $this->companyId = random_int(1, 500);
        $this->conversationId = random_int(1, 500);

        /** @var PactClientInterface|MockObject */
        $this->client = $this->getMockBuilder(PactClientBase::class)
            ->setConstructorArgs(['top-secret token do not look 0w0'])
            ->getMock();

        // Configure the stub.
        $this->client->expects($this->any())
        ->method('request')
        ->with(
            $this->anything(),
            $this->callback(function ($arg) {
                return $arg === $this->url;
            })
        )
        ->will($this->returnValue(Factory::response(200, [], '{"status":"ok"}')));

        $this->messageService = new MessageService($this->client);
    }

    protected function prepareUrl($append = '', array $routeParams = [], array $query = [])
    {
        $template = $this->messageService->getRouteTemplate();
        $this->url = $this->messageService->formatEndpoint($template.$append, $routeParams, $query);
        return $this->url;
    }

    public function testNormalGetMessages()
    {
        $url = $this->prepareUrl('', [$this->companyId, $this->conversationId]);
            
        $response = $this->messageService->getMessages(
            $this->companyId,
            $this->conversationId
        );
        $this->assertSame('ok', $response->status);
    }

    public function testValidSortGetMessage()
    {
        foreach (['asc', 'desc'] as $sort) {
            $query = ['sort' => $sort];
            $url = $this->prepareUrl('', [$this->companyId, $this->conversationId], $query);
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
        $query = ['sort' => 'asdf'];
        $url = $this->prepareUrl('', [$this->companyId, $this->conversationId], $query);
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
            $query = ['per' => $fetchCount];
            $url = $this->prepareUrl('', [$this->companyId, $this->conversationId], $query);
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
            $query = ['per' => $fetchCount];
            $url = $this->prepareUrl('', [$this->companyId, $this->conversationId], $query);
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
        $url = $this->prepareUrl('', [$this->companyId, $this->conversationId]);
        foreach([null, []] as $attachments) {
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
        $url = $this->prepareUrl('', [$this->companyId, $this->conversationId]);
        $response = $this->messageService->sendMessage(
            $this->companyId,
            $this->conversationId,
            'Message body',
            [1.5]
        );
        $this->assertSame('ok', $response->status);
    }
}
