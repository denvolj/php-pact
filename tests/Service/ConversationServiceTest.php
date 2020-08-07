<?php

namespace Pact\Tests\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Factory;
use Pact\PactClientBase;
use Pact\PactClientInterface;
use Pact\Service\ConversationService;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ConversationServiceTest extends TestCase
{
    /** @var PactClientInterface|MockObject */
    private $client = null;
    
    private $companyId;
    private $conversationId;
    protected $url;

    /** @var ConversationService */
    private $service;

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
                $this->assertEquals($this->url, $arg);
                return true;
            })
        )
        ->will($this->returnValue(Factory::response(200, [], '{"status":"ok"}')));

        $this->service = new ConversationService($this->client);
    }

    protected function prepareUrl($append = '', array $routeParams = [], array $query = [])
    {
        $template = $this->service->getRouteTemplate();
        $this->url = $this->service->formatEndpoint($template.$append, $routeParams, $query);
        return $this->url;
    }

    /**
     * @dataProvider validDataSetGetConversation
     */
    public function testValidGetConversations($from, $per, $sort)
    {
        $query = ['from' => $from, 'per' => $per, 'sort' => $sort];
        $this->prepareUrl('', [$this->companyId], $query);
            
        $response = $this->service->getConversations(
            $this->companyId,
            $from,
            $per, 
            $sort
        );
        $this->assertSame('ok', $response->status);
    }

    public function validDataSetGetConversation()
    {
        return [
            [null, null, null],
            ['asdf', null, null],
            ['asdf', 50, null],
            ['asdf', 50, 'asc'],
            ['asdf', 50, 'desc'],
        ];
    }

    /**
     * @dataProvider invalidDataSetGetConversation
     */
    public function testInvalidArgumentsGetConversation($from, $per, $sort)
    {
        $this->expectException(InvalidArgumentException::class);
        $query = ['from' => $from, 'per' => $per, 'sort' => $sort];
        $this->prepareUrl('', [$this->companyId], $query);
            
        $response = $this->service->getConversations(
            $this->companyId,
            $from,
            $per, 
            $sort
        );
        $this->assertSame('ok', $response->status);
    }

    public function invalidDataSetGetConversation()
    {
        $longString = str_repeat('a', 256);
        return [
            'Exception if "from" length >= 256' => [$longString, null, null],
            'Exception if "per" is outside limits' => [null, 0, null],
            'Exception if "per" is outside limits(1)' => [null, 101, null],
            'Exception if sort direction is not asc or desc' => [null, null, 'safd']
        ];
    }


    /**
     * @dataProvider validDataSetCreateConversation
     */
    public function testValidCreateConversation(string $provider, array $providerParams)
    {
        $this->prepareUrl('', [$this->companyId]);
            
        $response = $this->service->createConversation(
            $this->companyId,
            $provider,
            $providerParams
        );
        $this->assertSame('ok', $response->status);
    }

    public function validDataSetCreateConversation()
    {
        return [
            ['whatsapp', ['phone'=>'88005553535']]
        ];
    }

    public function testValidGetDetails()
    {
        $this->prepareUrl('/%s', [$this->companyId, $this->conversationId]);
            
        $response = $this->service->getDetails(
            $this->companyId,
            $this->conversationId
        );
        $this->assertSame('ok', $response->status);
    }

    public function testValidUpdateAssignee()
    {
        $this->prepareUrl('/%s/assign', [$this->companyId, $this->conversationId]);
            
        $response = $this->service->updateAssignee(
            $this->companyId,
            $this->conversationId,
            random_int(1, 500)
        );
        $this->assertSame('ok', $response->status);
    }

    /**
     * @dataProvider invalidDataSetUpdateAssignee
     */
    public function testInvalidUpdateAssignee($assigneeId)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->prepareUrl('/%s/assign', [$this->companyId, $this->conversationId]);
            
        $response = $this->service->updateAssignee(
            $this->companyId,
            $this->conversationId,
            $assigneeId
        );
        $this->assertSame('ok', $response->status);
    }

    public function invalidDataSetUpdateAssignee()
    {
        return [
            [0]
        ];
    }
}
