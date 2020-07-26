<?php

namespace Pact\Service;

interface ServiceInterface
{
    public function getRoute(...$params);
    //public function request($method);
}
