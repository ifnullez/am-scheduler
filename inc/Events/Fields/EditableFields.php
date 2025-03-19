<?php

namespace AM\Scheduler\Events\Fields;

use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Tasks\Models\TasksModel;

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
                    "rows" => 12,
                ],
            ],
            "task_id" => [
                "type" => "select",
                "label" => "Task",
                "options" => TasksModel::getInstance()->getAllAsOptionsArray(),
            ],
            "author_id" => [
                "type" => "hidden",
                "value" => get_current_user_id(),
            ],
            "id" => [
                "type" => "hidden",
            ],
            "action" => [
                "id" => self::$args["table_name"],
                "type" => "hidden",
                "value" => self::$args["table_name"],
            ],
            "slug_name" => [
                "id" => self::$args["slug"],
                "type" => "hidden",
                "value" => self::$args["slug"],
            ],
        ];
    }
}
