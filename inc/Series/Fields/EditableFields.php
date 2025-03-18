<?php

namespace MHS\Series\Fields;

use MHS\Base\Helpers\StaticHelper;
use MHS\Base\Traits\Singleton;
use MHS\Events\Models\EventsModel;
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
            "event_id" => [
                "type" => "select",
                "label" => "Event",
                "options" => StaticHelper::getFromArray(EventsModel::getInstance()->getAll(), "title"),
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
