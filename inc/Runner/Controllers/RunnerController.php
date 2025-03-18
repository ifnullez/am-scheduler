<?php

namespace MHS\Runner\Controllers;

use MHS\Series\Models\SeriesModel;

class RunnerController
{

    public function getSeriesForCurrentDateTime(
        string $modify = "+0 days"
    ): ?array {
        return SeriesModel::getInstance()->getForExecute(modify: $modify);
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
