<?php

namespace AM\Scheduler\Api\V1;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Api\V1\Endpoints\EventsEndpoint;
use AM\Scheduler\Base\Traits\Singleton;

class Api
{
    use Singleton;

    private ?ApiLoader $loader;
    private ?array $endpoints;

    private function __construct(?ApiLoader $loader = null)
    {
        $this->loader = $loader;
        $this->endpoints = [EventsEndpoint::getInstance($this->loader)];
    }
}
