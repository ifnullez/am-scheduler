<?php

namespace AM\Scheduler\Utils\Views\Enums;

enum Screen: String
{
    case LIST = "list";
    case EDIT = "edit";
    case DELETE = "delete";
    case NEW = "new";

    public function get(): string
    {
        return match ($this) {
            self::LIST => "list",
            self::EDIT => "edit",
            self::DELETE => "delete",
            self::NEW => "new",
            default => "list",
        };
    }
}
