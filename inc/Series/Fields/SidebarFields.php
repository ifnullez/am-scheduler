<?php

namespace MHS\Series\Fields;

use MHS\Base\Traits\Singleton;
use MHS\Groups\Controllers\GroupsController;
use MHS\Members\Controllers\MembersController;
use MHS\Rrule\Enums\ActionsEnum;

class SidebarFields
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
            "starting_at" => [
                "type" => "date",
                "label" => "Starting At",
                "placeholder" => "Select start date"
            ],
            "action" => [
                "id" => self::$args["table_name"],
                "type" => "select",
                "label" => "Action to run",
                "options" => ActionsEnum::array(),
            ],
            "amount" => [
                "type" => "number",
                "label" => "Amount",
            ],
            "group_id" => [
                "type" => "select",
                "label" => "Group",
                "options" => GroupsController::getAll(),
            ],
            "members_ids[]" => [
                "id" => self::$args["table_name"],
                "type" => "select",
                "label" => "Select members",
                "attributes" => [
                    "size" => 14,
                    "multiple" => true,
                ],
                "options" => (new MembersController())->getAll(),
            ],
        ];
    }
}
