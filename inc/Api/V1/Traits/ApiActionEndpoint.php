<?php

namespace MHS\Api\V1\Traits;

trait ApiActionEndpoint
{
    public function onCall($request): void
    {
        // get class and methods from
        $class = $request->get_param('class');
        $method = $request->get_param("method");
        $path = "MHPK\\Actions\\Action\\";

        // check if class and method are exists
        if (!empty($class) && !empty($method) && class_exists("{$path}{$class}") && method_exists("{$path}{$class}", $method)) {
            // call needed class method from class placed in Actions/Action
            "{$path}{$class}"::getInstance()->$method();
        } else {
            wp_send_json_error([
                "message" => "Wrong class or method used, please check it!"
            ]);
        }
    }
}
