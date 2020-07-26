<?php

namespace Pact\Service;

use Pact\Exception\ServiceNotExistException;
use Pact\Service\MessageService;

class ServiceFactory
{
    private static $instance = null;

    private $services = [];

    private $mapping = [
        'message' => MessageService::class
    ];

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __get($serviceName)
    {
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName];
        }

        if (array_key_exists($serviceName, $this->mapping)) {
            $service = new $this->mapping[$serviceName]();
            $this->services[$serviceName] = $service;

            return $service;
        }

        throw new ServiceNotExistException("Service ${serviceName} doesn't exist");
    }

    public function __call($serviceName, $arg)
    {
        $service = $this->__get($serviceName);

        return $service->createApiObject(...$arg);
    }
}
