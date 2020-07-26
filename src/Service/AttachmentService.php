<?php

namespace Pact\Service;

use Pact\HttpClient\HttpMethods;

class AttachmentService extends AbstractService
{
    protected $endpoint = 'companies/%s/conversations/%s/messages/attachments';

    /**
     * @param int id of the company
     * @param int id of the conversation
     * @param string|resource attachment location
     * @return Json|null
     */
    public function uploadAttachment($companyId, $conversationId, $attachment)
    {
        $response = $this->request(HttpMethods::POST, static::buildPath($path, $companyId, $conversationId), [], [
            'file' => $attachment
        ]);

        if ($response->isOK()) {
            return json_decode($response->getContent())->data;
        }
        return null;
    }
}
