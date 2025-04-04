<?php

namespace AM\Scheduler\Migrations;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Migrations\Schemas\EventSchema;

final class MigrationsLoader
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
