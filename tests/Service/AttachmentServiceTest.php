<?php

namespace Pact\Tests\Service;

use Pact\Exception\FileNotFoundException;
use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\PactClientBase;
use Pact\PactClientInterface;
use Pact\Service\AttachmentService;
use PHPUnit\Framework\TestCase;

class AttachmentServiceTest extends TestCase
{
    private $client = null;
    private $attachmentService = null;
    private $companyId = null;
    private $conversationId = null;

    protected function setUp(): void
    {
        /** @var PactClientInterface */
        $this->client = $this->getMockBuilder(PactClientBase::class)
            ->setConstructorArgs(['top-secret token do not look 0w0'])
            ->getMock();

        $this->attachmentService = new AttachmentService($this->client);
    }

    protected function prepareMock(array $query = [])
    {
        $this->conversationId = random_int(1, 500);
        $this->companyId = random_int(1, 500);
        $this->uri = $this->attachmentService->getRoute([$this->companyId, $this->conversationId], $query);

        // Configure the stub.
        $this->client->expects($this->any())
            ->method('request')
            ->will($this->returnValue(Factory::response(200, [], '{"status":"ok"}')));
    }

    public function testNormalUploadAttachment()
    {
        $this->prepareMock();
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
        $this->prepareMock();
        
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
        $this->prepareMock();
        
        $response = $this->attachmentService->uploadFile(
            $this->companyId,
            $this->conversationId,
            []
        );
        $this->assertSame('ok', $response->status);
    }
}
