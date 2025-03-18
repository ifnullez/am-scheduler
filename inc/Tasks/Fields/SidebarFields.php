<?php
namespace MHS\Tasks\Fields;

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
            "action" => [
                "id" => self::$args["table_name"],
                "type" => "select",
                "label" => "Action to run on each call",
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
