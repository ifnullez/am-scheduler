<?php

namespace MHS\Runner\Abstractions;

abstract class RunnerActionAbstraction
{
    protected ?string $action = "";

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
