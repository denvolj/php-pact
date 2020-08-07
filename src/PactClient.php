<?php

namespace Pact;

use Pact\Service\ServiceFactory;

/**
 * @param \Pact\Service\ConversationService $messages
 * @param \Pact\Service\MessageService $messages
 * @param \Pact\Service\AttachmentService $attachments
 */
class PactClient extends PactClientBase
{
    /** @var ServiceFactory */
    protected $services = null;

    public function __construct($config)
    {
        parent::__construct($config);

        $this->services = new ServiceFactory($this);
    }

    public function __get($serviceName)
    {
        return $this->services->{$serviceName};
    }
}
