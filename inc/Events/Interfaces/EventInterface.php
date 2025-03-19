<?php

namespace AM\Scheduler\Events\Interfaces;

interface EventInterface
{
    public function __get(string $property): mixed;
}
