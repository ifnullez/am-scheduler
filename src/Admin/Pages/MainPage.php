<?php

namespace AM\Scheduler\Admin\Pages;

use AM\Scheduler\Admin\Abstractions\AdminPage;
use AM\Scheduler\Admin\Traits\AdminPageTrait;

class MainPage extends AdminPage
{
    use AdminPageTrait;

    public function init(): void
    {
        $this->setPageTitle("Scheduler");
        $this->setmenuName("Events");

        add_menu_page(
            __($this->menuName, "ams"),
            __($this->pageTitle, "ams"),
            "manage_options",
            $this->parent,
            [$this, "callback"],
            "dashicons-calendar",
            $this->position
        );
        // add_action("admin_menu", function () {
        //     add_menu_page(
        //         __("Events", "ams"),
        //         __("Scheduler", "ams"),
        //         "manage_options",
        //         $this->parent,
        //         [$this, "callback"],
        //         "dashicons-calendar",
        //         $this->position
        //     );
        // });
    }
}
