<?php

namespace MHS\Runner\Interfaces;

interface RunnerActionInterface
{
    public function onCall(array $series = []): void;
}
