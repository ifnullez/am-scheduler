<?php

namespace AM\Scheduler\Runner\Interfaces;

interface RunnerActionInterface
{
    public function onCall(array $series = []): void;
}
