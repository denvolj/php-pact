<?php

namespace Pact\Tests\Service;

use Pact\PactClientBase;
use PHPUnit\Framework\TestCase;

class MessageServiceTest extends TestCase
{
    const SENSITIVE_FILE = __DIR__ . '/../data/data.sensitive.json';
    const EXECUTE = true;

    /** @var string */
    private static $token;

    private static $company_id;
    private static $conversation_id;

    /** @var PactClientBase */
    private $pact_client;
    
    public static function setUpBeforeClass(): void
    {
        if (file_exists(static::SENSITIVE_FILE)) {
            $str = file_get_contents(static::SENSITIVE_FILE);
            $json = json_decode($str);
            static::$token = $json->token;
            static::$company_id = $json->company;
            static::$conversation_id = $json->conversation;
        }
    }

    protected function setUp(): void
    {
        if (!static::EXECUTE) {
            $this->markTestSkipped('Skipped due prevent api spam');
        }
        $this->pact_client = new PactClientBase(static::$token);
    }

    protected function tearDown(): void
    {
        unset($this->pact_client);
    }

    public function testValidGetMessagesShouldReturnOK()
    {
        $this->markTestSkipped('Works fine');
        $response = $this->pact_client->messages->getMessages(static::$company_id, static::$conversation_id);

        $this->assertNotNull($response);
    }

    public function testValidSendMessageShouldReturnOK()
    {
        $this->markTestSkipped('Works fine');
        $response = $this->pact_client->messages->sendMessage(static::$company_id, static::$conversation_id, 'Da hax');

        $this->assertNotNull($response);
        $this->assertObjectHasAttribute('state', $response);
    }

    public function testValidUploadAttachmentShouldReturnOK()
    {
        $this->markTestSkipped('Works fine');
        $file = __DIR__ . '/../data/fennec.png';
        $response = $this->pact_client->messages->uploadAttachment(static::$company_id, static::$conversation_id, $file);

        $this->assertNotNull($response);
        $this->assertObjectHasAttribute('external_id', $response);
    }

    public function testValidSendMessageWithAttachmentShouldReturnOK()
    {
        $file = __DIR__ . '/../data/fennec.png';
        $response = $this->pact_client->messages->uploadAttachment(static::$company_id, static::$conversation_id, $file);

        $this->assertNotNull($response);
        $this->assertObjectHasAttribute('external_id', $response);
        $file = __DIR__ . '/../data/fennec.png';
        $response = $this->pact_client->messages->sendMessage(static::$company_id, static::$conversation_id, "Woah!", [$response->external_id]);

        $this->assertNotNull($response);
        $this->assertObjectHasAttribute('state', $response);
    }
}
