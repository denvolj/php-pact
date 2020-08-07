<?php

namespace Pact\Tests\Service;

use Pact\Exception\FileNotFoundException;
use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\PactClientBase;
use Pact\PactClientInterface;
use Pact\Service\AttachmentService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AttachmentServiceTest extends TestCase
{
    /** @var PactClientInterface|MockObject */
    private $client = null;

    /** @var AttachmentService */
    private $attachmentService = null;
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

        $this->attachmentService = new AttachmentService($this->client);
    }

    protected function prepareUrl($append = '', array $routeParams = [], array $query = [])
    {
        $template = $this->attachmentService->getRouteTemplate();
        $this->url = $this->attachmentService->formatEndpoint($template.$append, $routeParams, $query);
        return $this->url;
    }

    public function testNormalUploadAttachment()
    {
        $this->prepareUrl('', [$this->companyId, $this->conversationId]);
        $data = [
            fopen(__DIR__.'/../data/fennec.png', 'r'),
            __DIR__.'/../data/fennec.png', 
            'http://fennecs.fc/nya.png'
        ];
        foreach ($data as $file) {
            $response = $this->attachmentService->uploadFile(
                $this->companyId,
                $this->conversationId,
                $file
            );
            $this->assertSame('ok', $response->status);
        }
    }

    public function testAttachNotExistingFileThrowsFileNotFound()
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessageMatches('/^File .+? not found/');
        
        $response = $this->attachmentService->uploadFile(
            $this->companyId,
            $this->conversationId,
            __DIR__.'/../not-existing.file'
        );
        $this->assertSame('ok', $response->status);
    }

    public function testInvalidAttachmentSourceThrowsInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attachment must be string or resource or StreamInterface');
        
        $response = $this->attachmentService->uploadFile(
            $this->companyId,
            $this->conversationId,
            []
        );
        $this->assertSame('ok', $response->status);
    }
}
