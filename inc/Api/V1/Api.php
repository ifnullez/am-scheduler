<?php

namespace MHS\Api\V1;

use MHS\Api\ApiLoader;
use MHS\Api\V1\Endpoints\{SeriesEndpoint};
use MHS\Base\Traits\Singleton;

class Api extends ApiLoader
{
    use Singleton;

    private array $endpoints = [];

    private function __construct()
    {
        $this->endpoints = [
            SeriesEndpoint::getInstance()
        ];
    }
}
