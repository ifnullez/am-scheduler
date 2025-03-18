<?php

namespace MHS\Api;

use MHS\Base\Traits\Singleton;
use MHS\Api\V1\Api;

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
