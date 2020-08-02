<?php

namespace Pact\Service;

use Pact\Exception\InvalidArgumentException;
use Pact\Http\Methods;
use PHPUnit\Util\Json;

/**
 * Класс отвечает за подготовление запроса 
 */
class MessageService extends AbstractService
{
    protected static $endpoint = 'companies/%s/conversations/%s/messages';

    private function isSortCorrect($sort)
    {
        return 0 === strcmp('asc', $sort) 
            || 0 === strcmp('desc', $sort);
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
        $uri = static::getRoute(
                [$companyId, $conversationId], 
                $query
            );

        return $this->request(Methods::GET, $uri);
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
        $query = [
            'message' =>  $message,
            'attachments_ids' => $attachments
        ];

        $uri = static::getRoute(
            [$companyId, $conversationId], 
            $query
        );

        return $this->request(Methods::POST, $uri);
    }
}
