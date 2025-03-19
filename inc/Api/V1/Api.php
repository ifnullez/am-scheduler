<?php

namespace AM\Scheduler\Api\V1;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Api\V1\Endpoints\{SeriesEndpoint};
use AM\Scheduler\Base\Traits\Singleton;

class Api extends ApiLoader
{
    use Singleton;

    private array $endpoints = [];

    private function __construct()
    {
        $this->endpoints = [SeriesEndpoint::getInstance()];
    }
}
