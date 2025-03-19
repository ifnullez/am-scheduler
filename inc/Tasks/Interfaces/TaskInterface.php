<?php

namespace AM\Scheduler\Tasks\Interfaces;

interface TaskInterface
{
    public function __get(string $property): mixed;
}
