<?php

namespace AM\Scheduler\Api\V1\Endpoints;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Utils\Traits\Singleton;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Response;
use WP_REST_Server;

class EventEndpoints extends WP_REST_Controller
{
    use Singleton;

    private ApiLoader $loader;
    private string $endpoint = "event";

    private function __construct(ApiLoader $loader)
    {
        $this->loader = $loader;
        add_action("rest_api_init", [$this, "register_routes"]);
    }
    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        register_rest_route($this->loader->namespace, "/{$this->endpoint}", [
            [
                "methods" => WP_REST_Server::READABLE,
                "callback" => [$this, "get_items"],
                "permission_callback" => [$this, "get_items_permissions_check"],
                "args" => [],
            ],
            [
                "methods" => WP_REST_Server::CREATABLE,
                "callback" => [$this, "create_item"],
                "permission_callback" => [
                    $this,
                    "create_item_permissions_check",
                ],
                "args" => $this->get_endpoint_args_for_item_schema(true),
            ],
        ]);
        register_rest_route(
            $this->loader->namespace,
            "/{$this->endpoint}/(?P<id>[\d]+)",
            [
                [
                    "methods" => WP_REST_Server::READABLE,
                    "callback" => [$this, "get_item"],
                    "permission_callback" => [
                        $this,
                        "get_item_permissions_check",
                    ],
                    "args" => [
                        "context" => [
                            "default" => "view",
                        ],
                    ],
                ],
                [
                    "methods" => WP_REST_Server::EDITABLE,
                    "callback" => [$this, "update_item"],
                    "permission_callback" => [
                        $this,
                        "update_item_permissions_check",
                    ],
                    "args" => $this->get_endpoint_args_for_item_schema(false),
                ],
                [
                    "methods" => WP_REST_Server::DELETABLE,
                    "callback" => [$this, "delete_item"],
                    "permission_callback" => [
                        $this,
                        "delete_item_permissions_check",
                    ],
                    "args" => [
                        "force" => [
                            "default" => false,
                        ],
                    ],
                ],
            ]
        );
        register_rest_route(
            $this->loader->namespace,
            "/{$this->endpoint}/schema",
            [
                "methods" => WP_REST_Server::READABLE,
                "callback" => [$this, "get_public_item_schema"],
                "permission_callback" => [
                    $this,
                    "create_item_permissions_check",
                ],
            ]
        );
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $items = []; //do a query, call another class, etc
        $data = [];
        foreach ($items as $item) {
            $itemdata = $this->prepare_item_for_response($item, $request);
            $data[] = $this->prepare_response_for_collection($itemdata);
        }

        return new WP_REST_Response($data, 200);
    }

    /**
     * Get one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item($request)
    {
        //get parameters from request
        $params = $request->get_params();
        $item = []; //do a query, call another class, etc
        $data = $this->prepare_item_for_response($item, $request);

        //return a response or error based on some conditional
        if (1 == 1) {
            return new WP_REST_Response($data, 200);
        } else {
            return new WP_Error("code", __("message", "text-domain"));
        }
    }

    /**
     * Create one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_item($request)
    {
        $item = $this->prepare_item_for_database($request);

        if (function_exists("slug_some_function_to_create_item")) {
            /** TODO: need to implement $data */
            $data = null;
            if (is_array($data)) {
                return new WP_REST_Response($data, 200);
            }
        }

        return new WP_Error("cant-create", __("message", "text-domain"), [
            "status" => 500,
        ]);
    }

    /**
     * Update one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function update_item($request)
    {
        $item = $this->prepare_item_for_database($request);

        if (function_exists("slug_some_function_to_update_item")) {
            /** TODO: need to implement $data */
            $data = null;
            if (is_array($data)) {
                return new WP_REST_Response($data, 200);
            }
        }

        return new WP_Error("cant-update", __("message", "text-domain"), [
            "status" => 500,
        ]);
    }

    /**
     * Delete one item from the collection
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function delete_item($request)
    {
        $item = $this->prepare_item_for_database($request);

        if (function_exists("slug_some_function_to_delete_item")) {
            /** TODO: need to implement $data */
            $deleted = null;
            if ($deleted) {
                return new WP_REST_Response(true, 200);
            }
        }

        return new WP_Error("cant-delete", __("message", "text-domain"), [
            "status" => 500,
        ]);
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        //return true; <--use to make readable by all
        return current_user_can("edit_something");
    }

    /**
     * Check if a given request has access to get a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_item_permissions_check($request)
    {
        return $this->get_items_permissions_check($request);
    }

    /**
     * Check if a given request has access to create items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function create_item_permissions_check($request)
    {
        return current_user_can("edit_something");
    }

    /**
     * Check if a given request has access to update a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function update_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }

    /**
     * Check if a given request has access to delete a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function delete_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }

    /**
     * Prepare the item for create or update operation
     *
     * @param WP_REST_Request $request Request object
     * @return WP_Error|object $prepared_item
     */
    protected function prepare_item_for_database($request)
    {
        return [];
    }

    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     * @return mixed
     */
    public function prepare_item_for_response($item, $request)
    {
        return [];
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params()
    {
        return [
            "page" => [
                "description" => "Current page of the collection.",
                "type" => "integer",
                "default" => 1,
                "sanitize_callback" => "absint",
            ],
            "per_page" => [
                "description" =>
                    "Maximum number of items to be returned in result set.",
                "type" => "integer",
                "default" => 10,
                "sanitize_callback" => "absint",
            ],
            "search" => [
                "description" => "Limit results to those matching a string.",
                "type" => "string",
                "sanitize_callback" => "sanitize_text_field",
            ],
        ];
    }
}
