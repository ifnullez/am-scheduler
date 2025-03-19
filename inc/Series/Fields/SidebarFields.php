<?php

namespace AM\Scheduler\Series\Fields;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Groups\Controllers\GroupsController;
use AM\Scheduler\Members\Controllers\MembersController;
use AM\Scheduler\Rrule\Enums\ActionsEnum;

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
                "placeholder" => "Select start date",
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
