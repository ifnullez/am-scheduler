<?php

namespace AM\Scheduler\Api;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Api\V1\Api;

class ApiLoader
{
    use Singleton;

    protected string $namespace = "mh-scheduler/v1";

    private Api $api;

    private function __construct()
    {
        $this->api = Api::getInstance($this->namespace);
    }
}
