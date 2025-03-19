<?php

namespace AM\Scheduler\Series\Fields;

use AM\Scheduler\Base\Helpers\StaticHelper;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Events\Models\EventsModel;
use AM\Scheduler\Rrule\Enums\ActionsEnum;

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
            "event_id" => [
                "type" => "select",
                "label" => "Event",
                "options" => StaticHelper::getFromArray(
                    EventsModel::getInstance()->getAll(),
                    "title"
                ),
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
