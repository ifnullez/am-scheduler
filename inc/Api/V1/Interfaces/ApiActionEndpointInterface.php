<?php

namespace MHS\Api\V1\Interfaces;

interface ApiActionEndpointInterface
{
    public function onCall($request): void;
    public function endpoints(): void;
}
