<?php

namespace Pact\Tests\Service;

use Pact\Service\AbstractService;
use PHPUnit\Framework\TestCase;

class AbstractServiceTest extends TestCase
{
    private $reflectedBuildPath = null;

    private function callBuildPath($path, ...$ids)
    {
        if (null == $this->reflectedBuildPath) {
            $reflectedClass = new \ReflectionClass(AbstractService::class);
            $this->reflectedBuildPath = $reflectedClass->getMethod('buildPath');
            $this->reflectedBuildPath->setAccessible(true);
        }

        return $this->reflectedBuildPath->invokeArgs(
            null,
            [
                $path,
                ...$ids
            ]
        );
    }

    public function testBuildPathWithInvalidParametersThrowsInvalidArgumentException()
    {
        $this->expectErrorMessage('The resource ID cannot be null or whitespace.');
        $this->assertSame('/test/5/path/df/', $this->callBuildPath('/test/%s/path/%s/', '', 'df'));
        
        $this->expectError(\InvalidArgumentException::class);
    }

    public function testBuildPathWithValidParametersReturnsValidUrnString()
    {
        $this->assertSame('/5/test/df/', $this->callBuildPath('/%s/test/%s/', 5, 'df'));
        $this->assertSame('/%24%25tst/path/', $this->callBuildPath('/%s/path/', '$%tst'));
    }
}