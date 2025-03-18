<?php

namespace MHS\Api\V1\Endpoints;

use WP_REST_Server;
use MHS\Api\V1\Api;
use MHS\Base\Traits\Singleton;
use MHS\Series\Controllers\SeriesController;

class SeriesEndpoint extends Api
{
    use Singleton;

    private function __construct()
    {
        add_action("rest_api_init", [$this, "endpoints"]);
    }

    public function endpoints(): void
    {
        register_rest_route($this->namespace, "/series", [
            [
                "methods" => WP_REST_Server::CREATABLE,
                "callback" => [SeriesController::getInstance(), "bulkActions"],
                "permission_callback" => fn () => true
            ]
        ]);
    }
}
