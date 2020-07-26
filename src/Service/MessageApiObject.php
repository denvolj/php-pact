<?php

namespace Pact\Service;

use DateTimeInterface;
use Pact\Service\MessageService;
use Pact\Service\ApiObjectInterface;

class MessageApiObject implements ApiObjectInterface
{
    /**
     * @var mixed Unique identifier of message in external system 
     */
    private $id = null;
    
    /**
     * @var int Id of company in Pact
     */
    private $companyId;

    /**
     * @var int Id of channel in Pact
     */
    private $channelId;

    /**
     * @var string Delivery channel type (vk, whatsapp...)
     */
    private $channelType;

    /**
     * @var string Message body
     */
    private string $body;

    /**
     * @var bool is message incoming
     */
    private bool $isIncome;
    
    /**
     * @var DateTimeInterface time when message was delivered
     */
    private DateTimeInterface $createdAt;

    /**
     * @var array 
     */
    private $attachments = [];

    /**
     * Gets message service for making requests
     * @todo move method due (S)OLID?
     * 
     * @return MessageService
     */
    public function getService(): MessageService
    {
        return ServiceFactory::getInstance()->message;
    }

    /**
     * Creates a new message
     * 
     * @param string Message body
     * @param mixed Unique identifier of message in external system 
     * @param int Id of company in Pact
     * @param int Id of channel in Pact
     * @param string Delivery channel type (vk, whatsapp...)
     * @param bool Is message incoming
     * @param DateTimeInterface
     * @return MessageApiObject
     */
    public static function factory(
        string $body = '', 
        $id = null, 
        int $companyId = null,
        int $channelId = null, 
        string $channelType = '', 
        bool $isIncome = false, 
        DateTimeInterface $createdAt = null
    ) {
        $instance = new static();
        return $instance->setId($id)
            ->setBody($body)
            ->setCompanyId($companyId)
            ->setChannelId($channelId)
            ->setChannelType($channelType)
            ->setIsIncoming($isIncome)
            ->setCreationTime($createdAt);
    }

    /**
     * Gets unique identifier in external system
     * 
     * @return mixed 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets Unique identifier of message and provides method-chaining
     * 
     * @param mixed 
     * @return MessageApiObject
     */
    public function setId($id): MessageApiObject
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets company id
     * 
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }


    /**
     * Sets id of company to message and provides method-chaining
     * 
     * @param int
     * @return MessageApiObject
     */
    public function setCompanyId(int $companyId): MessageApiObject
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * Gets id of company from message
     * @return int 
     */
    public function getChannelId(): int
    {
        return $this->channelId;
    }


    /**
     * Sets id of channel to message and provides method-chaining
     * 
     * @param int 
     * @return MessageApiObject
     */
    public function setChannelId(int $channelId): MessageApiObject
    {
        $this->channelId = $channelId;
        return $this;
    }


    /**
     * Gets channel type
     * 
     * @return string
     */
    public function getChannelType(): string
    {
        return $this->channelType;
    }

    /**
     * Sets channel type of message and provides method-chaining
     * 
     * @param string 
     * @return MessageApiObject
     */
    public function setChannelType(string $channelType): MessageApiObject
    {
        $this->channelType = $channelType;
        return $this;
    }


    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string Message body
     * @return MessageApiObject
     */
    public function setBody(string $body): MessageApiObject
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Is message incoming?
     * 
     * @return bool
     */
    public function isIncoming(): bool
    {
        return $this->isIncome;
    }

    /**
     * Marks message incoming or not and provides method chaining
     * 
     * @param bool 
     * @return MessageApiObject
     */
    public function setIsIncoming(bool $isIncome): MessageApiObject
    {
        $this->isIncome = $isIncome;
        return $this;
    }

    /**
     * Gets time when message was created 
     * 
     * @note normally time sets after message delivery
     * @return DateTimeInterface
     */
    public function getCreationTime(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Sets time when message was created and provides method chaining
     * 
     * @note normally time sets after message delivery
     * @param DateTimeInterface  
     * @return MessageApiObject
     */
    public function setCreationTime(DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Send file to Pact to make attachments
     * @todo move method due (S)OLID?
     * 
     * @param string|resource 
     */
    public function attachFile()
    {

    }

    /**
     * Send message
     * @todo move method due (S)OLID?
     */
    public function send()
    {
        $this->getService()->sendMessage(
            $this->companyId,
            $this->channelId,
            $this->body,
        );
    }
}
