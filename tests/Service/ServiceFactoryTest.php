<?php

namespace Pact\Tests\Service;

use Pact\Exception\ServiceNotExistException;
use Pact\PactClientBase;
use Pact\Service\ServiceFactory;
use Pact\Service\ServiceInterface;
use PHPUnit\Framework\TestCase;

class ServiceFactoryTest extends TestCase
{
    /** @var ServiceFactory */
    private $factory;

    protected function setUp(): void
    {
        $client = new PactClientBase('top-secret token do not look 0w0');
        $this->factory = new ServiceFactory($client);
    }

    /**
     * Test get unknown service throws ServiceNotFoundException
     */
    public function testGetNotExistingServiceThrowsServiceNotFoundException()
    {
        $this->expectException(ServiceNotExistException::class);
        $this->factory->TotallyNotExistingService;
    }

    /**
     * Test get implemented service will return implemented ServiceInterface
     */
    public function testGetImplementedServiceReturnService()
    {
        $service = $this->factory->messages;
        $this->assertNotEmpty($service);
        $this->assertInstanceOf(ServiceInterface::class, $service);
    }
}
