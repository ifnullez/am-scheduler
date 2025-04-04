<?php

namespace AM\Scheduler\Entities;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Entities\Schemas\EventSchema;

final class EntitiesLoader
{
    use Singleton;

    private ?array $schemas = [];

    private function __construct()
    {
        $this->schemas = [EventSchema::getInstance()];
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
