<?php

namespace AM\Scheduler\Assets\Localize;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Utils\Traits\{Singleton, GetterTrait};

class AdminLocalize
{
    use Singleton, GetterTrait;

    private array $i18n = [];

    private function __construct()
    {
        $this->i18n = [
            "public" => "test",
            "rest" => [
                "url" => esc_url(
                    get_rest_url(path: ApiLoader::getInstance()->namespace)
                ),
            ],
            "pupa" => "loopa",
        ];
    }
}
