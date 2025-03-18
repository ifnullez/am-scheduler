<?php

namespace MHS\Runner\Traits;

trait SchedulerRunnerTrait
{
    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
