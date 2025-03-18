<?php

namespace MHS\Tasks\Fields;

use MHS\Base\Traits\Singleton;
use MHS\Rrule\Enums\ActionsEnum;

class EditableFields
{
    use Singleton;

    private static array $args = [];

    public function __construct(array $args = [])
    {
        self::$args = $args;
    }

    public static function getFields(): ?array
    {
        return [
            "title" => [
                "type" => "text",
                "label" => "Title",
            ],
            "description" => [
                "type" => "textarea",
                "label" => "Description",
                "attributes" => [
                    "rows" => 12
                ]
            ],
            "author_id" => [
                "type" => "hidden",
                "value" => get_current_user_id(),
            ],
            "id" => [
                "type" => "hidden",
            ],
            "slug_name" => [
                "id" => self::$args["slug"],
                "type" => "hidden",
                "value" => self::$args["slug"],
            ],
        ];
    }
}
