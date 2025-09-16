<?php

namespace AM\Scheduler\Api\V1;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Api\V1\Endpoints\EventEndpoints;
use AM\Scheduler\Utils\Traits\Singleton;

class Api
{
    use Singleton;

    private ApiLoader $loader;
    private array $endpoints = [];

    private function __construct(ApiLoader $loader)
    {
        $this->loader = $loader;
        $this->endpoints = [EventEndpoints::getInstance($this->loader)];
    }
}
