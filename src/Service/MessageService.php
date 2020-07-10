<?php

namespace Pact\Service;

use Pact\HttpClient\HttpMethods;
use Pact\HttpClient\ApiRequest;
use PHPUnit\Util\Json;

class MessageService extends AbstractService
{
    /**
     * Get conversation messages
     * @see https://pact-im.github.io/api-doc/#get-conversation-messages
     * 
     * @param int id of the company
     * @param int id of the conversation
     * @param string Next page token geted from last request. 
     * Not valid or empty token return first page
     * @param int Number of elements per page. Default: 50
     * @param string We sort results by created_at. Change sorting direction. Avilable values: asc, desc. Default: asc.
     * @return Json|null
     */
    public function getMessages($company_id, $conversation_id, string $from=null, int $per=null, string $sort=null)
    {
        $path = 'companies/%s/conversations/%s/messages';
        $query = ['from' => $from, 'per' => $per, 'sort' => $sort];

        if ($per !== null && ($per < 1 || $per > 100)) {
            throw new \InvalidArgumentException('Number of fetching elements must be between 1 and 100.');
        }

        if ($sort !== null && (0 !== strcmp('asc', $sort) || 0 !== strcmp('desc', $sort))) {
            throw new \InvalidArgumentException('Sort parameter must be asc or desc');
        }

        $response = $this->request(HttpMethods::GET, static::buildPath($path, $company_id, $conversation_id), $query);
        if ($response->isOK()) {
            return json_decode($response->getContent())->data;
        }
        return null;
    }

    /**
     * @see https://pact-im.github.io/api-doc/#send-message
     * @param int id of the company
     * @param int id of the conversation
     * @param string Message text
     * @param array<int>|null attachments
     */
    public function sendMessage($company_id, $conversation_id, $message, $attachments = null)
    {
        $path = 'companies/%s/conversations/%s/messages';
        $response = $this->request(HttpMethods::POST, static::buildPath($path, $company_id, $conversation_id), [], http_build_query([
            'message' =>  $message,
            'attachments_ids' => $attachments
        ]));

        if ($response->isOK()) {
            return json_decode($response->getContent())->data;
        }
        return null;
    }

    /**
     * @param int id of the company
     * @param int id of the conversation
     * @param string attachment location
     * @return Json|null
     */
    public function uploadAttachment($company_id, $conversation_id, $attachment)
    {
        $path = 'companies/%s/conversations/%s/messages/attachments';
        $response = $this->request(HttpMethods::POST, static::buildPath($path, $company_id, $conversation_id), [], [
            'file' => $attachment
        ]);

        if ($response->isOK()) {
            return json_decode($response->getContent())->data;
        }
        return null;
    }
}