<?php

namespace AM\Scheduler\Base\Traits;

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
