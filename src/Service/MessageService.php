<?php

namespace Pact\Service;

use Pact\Service\MessageApiObject;
use Pact\Exception\InvalidArgumentException;
use Pact\Http\Methods;
use PHPUnit\Util\Json;

class MessageService extends AbstractService
{
    protected static $endpoint = 'companies/%s/conversations/%s/messages';


    /**
     * Creates new message related to company and conversation
     * 
     * @example $svc->createMessage($comp_id, $chan_id)
     *              ->setBody('Hello World!')
     *              ->attachFile($fileresource)
     *              ->attachFile($url_to_file)
     *              ->send();
     * @param int Id of company in Pact
     * @param int Id of conversation (channel) in Pact
     * @return MessageApiObject
     */
    public function createMessage($companyId, $conversationId): MessageApiObject
    {
        $message = new MessageApiObject();
        $message->setCompanyId($companyId)
            ->setChannelId($conversationId);

        return $message;
    }


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
    public function getMessages($companyId, $conversationId, string $from=null, int $per=null, string $sort=null)
    {
        $query = ['from' => $from, 'per' => $per, 'sort' => $sort];

        if ($per !== null && ($per < 1 || $per > 100)) {
            throw new InvalidArgumentException('Number of fetching elements must be between 1 and 100.');
        }

        if ($sort !== null && (0 !== strcmp('asc', $sort) || 0 !== strcmp('desc', $sort))) {
            throw new InvalidArgumentException('Sort parameter must be asc or desc');
        }

        $response = $this->request(HttpMethods::GET, static::buildPath(static::$endpoint, $companyId, $conversationId), $query);
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
    public function sendMessage($companyId, $conversationId, $message, $attachments = null)
    {
        $response = $this->request(HttpMethods::POST, static::buildPath(static::$endpoint, $companyId, $conversationId), [], http_build_query([
            'message' =>  $message,
            'attachments_ids' => $attachments
        ]));

        if ($response->isOK()) {
            return json_decode($response->getContent())->data;
        }
        return null;
    }
}
