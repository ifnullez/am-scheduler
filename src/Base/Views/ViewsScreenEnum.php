<?php

namespace AM\Scheduler\Base\Views;

enum ViewsScreenEnum: String
{
    case LIST = "list";
    case EDIT = "edit";
    case DELETE = "delete";
    case NEW = "new";

    // public function screen(): string
    // {
    //     return match ($this) {
    //         ViewsScreenEnum::LIST => "list",
    //         ViewsScreenEnum::EDIT => "edit",
    //         ViewsScreenEnum::DELETE => "delete",
    //         ViewsScreenEnum::NEW => "new",
    //         default => "list",
    //     };
    // }
}
