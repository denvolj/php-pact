<?php

namespace Pact\Service;

use Pact\Service\ServiceInterface;

interface ApiObjectInterface
{
    public function getService(): ServiceInterface;
}
