<?php

namespace AM\Scheduler\Utils\Traits;

trait GetterTrait
{
    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }
}
