<?php

namespace AM\Scheduler\Api;

use AM\Scheduler\Api\V1\Api;
use AM\Scheduler\Base\Traits\Singleton;

final class ApiLoader
{
    use Singleton;

    private ?Api $api;
    private ?string $version = "v1";
    private ?string $namespace = "am/scheduler";

    private function __construct()
    {
        $this->namespace = "{$this->namespace}/{$this->version}";
        $this->api = Api::getInstance($this);
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
