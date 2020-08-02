<?php

namespace Pact\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Methods;
use PHPUnit\Util\Json;

class MessageService extends AbstractService
{
    protected static $endpoint = 'companies/%s/conversations/%s/messages';

    private function isSortCorrect($sort)
    {
        return 0 === strcmp('asc', $sort) 
            || 0 === strcmp('desc', $sort);
    }

    /**
     * @param array Route parameters validation method
     * @throws InvalidArgumentException
     * @todo move some part of this method outside of class
     */
    protected function validateRouteParams($params)
    {
        [$companyId, $conversationId] = $params;
        if (!is_int($companyId)) {
            throw new InvalidArgumentException('Id of company must be integer');
        }
        if (!is_int($conversationId)) {
            throw new InvalidArgumentException('Id of conversation must be integer');
        }

        if ($companyId < 0) {
            throw new InvalidArgumentException('Id of company must be greater or equal than 0');
        }
        if ($conversationId < 0) {
            throw new InvalidArgumentException('Id of conversation must be greater or equal than 0');
        }
    }

    /**
     * Attachments must be integers - ids of uploaded in attachents (in Pact)
     * @param array|null Attachment list
     * @throws InvalidArgumentException
     */
    private function validateAttachments($attacments)
    {
        if ($attacments === null) {
            return;
        } 
        foreach ($attacments as $attacment) {
            if (!is_int($attacment)) {
                throw new InvalidArgumentException('Attachment must be integer');
            }
        }
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
        if ($per !== null && ($per < 1 || $per > 100)) {
            $msg = 'Number of fetching elements must be between 1 and 100.';
            throw new InvalidArgumentException($msg);
        }

        if ($sort !== null && !$this->isSortCorrect($sort)) {
            throw new InvalidArgumentException('Sort parameter must be asc or desc');
        }

        $query = ['from' => $from, 'per' => $per, 'sort' => $sort];

        return $this->request(Methods::GET, [$companyId, $conversationId], $query);
    }

    /**
     * @see https://pact-im.github.io/api-doc/#send-message
     * @param int id of the company
     * @param int id of the conversation
     * @param string Message text
     * @param array<int>|null attachments
     */
    public function sendMessage($companyId, $conversationId, string $message = null, array $attachments = null)
    {
        $this->validateAttachments($attachments);
        
        $body = [
            'message' =>  $message,
            'attachments_ids' => $attachments
        ];

        return $this->request(Methods::POST, [$companyId, $conversationId], [], [], $body);
    }
}
