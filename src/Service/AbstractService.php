<?php

namespace Pact\Service;

use Pact\PactClientInterface;
use Pact\Service\ApiObjectInterface;
use Pact\Service\ServiceInterface;
use Pact\Utils\UrlFormatter;

abstract class AbstractService implements ServiceInterface
{   
    /**
     * @var ApiObjectInterface
     */
    protected static $apiObjectClass = null;

    /**
     * @var string Formatted string contains pattern for route formatting
     * @example "/companies/%s/conversation/%s/
     */
    protected static string $routeTemplate = "";

    /**
     * @var PactClientInterface
     */
    protected $client;

    /**
     * Constructor
     * 
     * @param PactClientInterface
     */
    public function __construct(PactClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @var mixed substrings to insert in route template
     */
    public function getRoute(...$params) 
    {
        return UrlFormatter::format(static::$routeTemplate, ...$params);
    }
}
