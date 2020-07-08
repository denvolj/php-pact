<?php

namespace Pact\Services;

use Pact\PactClientBase;

class MessageService
{
    const SERVICE_NAME = 'messages';

    /** @var  */
    private $client = null;

    /**
     * @param PactClientBase
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    public function getServiceEndpoint($company_id, $conversation_id)
    {
        return "companies/{$company_id}/conversations/{$conversation_id}/messages";
    }

    public function getMessages($company_id, $conversation_id)
    {
        $endpoint = $this->getServiceEndpoint($company_id, $conversation_id);
        return $this->client->makeRequest($endpoint);
    }

    public function sendMessage()
    {
    }

    public function uploadAttachments()
    {
    }
}