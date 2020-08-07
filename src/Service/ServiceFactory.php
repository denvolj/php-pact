<?php

namespace Pact\Service;

use Pact\Exception\NotImplementedException;
use Pact\PactClientInterface;

/**
 * @property MessageService $messages
 * @property AttachmentService $attachments
 * @property ConversationService $conversations
 */
class ServiceFactory
{
    /** @var PactClientInterface */
    protected $client = null;

    /** @var array */
    protected $services = [];

    /** @var array */
    protected $mapping = [
        'messages' => MessageService::class,
        'attachments' => AttachmentService::class,
        'conversations' => ConversationService::class
    ];

    /**
     * Constructor
     * 
     * @param PactClientInterface 
     */
    public function __construct(PactClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Creating new instance of service if not done yet
     * and returns it back
     * 
     * @param string Name of service
     * @return ServiceInterface
     */
    public function __get($serviceName)
    {
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName];
        }

        if (array_key_exists($serviceName, $this->mapping)) {
            $service = new $this->mapping[$serviceName]($this->client);
            $this->services[$serviceName] = $service;

            return $service;
        }

        throw new NotImplementedException("Service ${serviceName} doesn't exist");
    }
}
