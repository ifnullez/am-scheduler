<?php

namespace AM\Scheduler\AdminPages;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\AdminPages\Pages\{
    EventsAdminPage,
    // SeriesAdminPage,
    TasksAdminPage
};

class AdminPagesLoader
{
    use Singleton;

    private string $menu_slug = "ams-scheduler";

    private $pages = [];

    private function __construct()
    {
        $this->pages = [
            EventsAdminPage::getInstance($this->menu_slug),
            TasksAdminPage::getInstance($this->menu_slug),
            // SeriesAdminPage::getInstance($this->menu_item_slug),
        ];
    }
}
