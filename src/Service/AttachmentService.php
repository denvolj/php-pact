<?php

namespace Pact\Service;

use Pact\Exception\FileNotFoundException;
use Pact\Exception\InvalidArgumentException;
use Pact\Http\Methods;
use Psr\Http\Message\StreamInterface;

class AttachmentService extends AbstractService
{
    protected $endpoint = 'companies/%s/conversations/%s/messages/attachments';

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
     * @param Resource|StreamInterface|string file to upload
     */
    private function prepareAttachment($file)
    {
        if (is_string($file)) {
            if (filter_var($file, FILTER_VALIDATE_URL)) {
                return ['file_url' => $file];
            } else if (file_exists($file)) {
                return ['file' => fopen($file, 'r')];
            } else {
                throw new FileNotFoundException("File ${file} not found");
            }
        } else if (is_resource($file) || $file instanceof StreamInterface) {
            return ['file' => $file];
        }

        $msg = 'Attachment must be string or resource or StreamInterface';
        throw new InvalidArgumentException($msg);
    }

    /**
     * Сreates an attachment which can be sent in message
     * @see https://pact-im.github.io/api-doc/#upload-attachments
     * 
     * @param int id of the company
     * @param int id of the conversation
     * @param Resource|StreamInterface|string file to upload
     * @return Json|null
     */
    public function uploadFile($companyId, $conversationId, $attachment)
    {
        $body = $this->prepareAttachment($attachment);

        return $this->request(Methods::POST, [$companyId, $conversationId], [], [], $body);
    }
}
