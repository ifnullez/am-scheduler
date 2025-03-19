<?php

namespace AM\Scheduler\AdminPages;

use AM\Scheduler\AdminPages\Pages\{
    EventsAdminPage,
    SeriesAdminPage,
    TasksAdminPage
};
use AM\Scheduler\Base\Traits\Singleton;

class AdminPagesLoader
{
    use Singleton;

    private string $menu_item_slug = "mh_scheduler";

    private $pages = [];

    private function __construct()
    {
        $this->pages = [
            EventsAdminPage::getInstance($this->menu_item_slug),
            SeriesAdminPage::getInstance($this->menu_item_slug),
            TasksAdminPage::getInstance($this->menu_item_slug),
        ];
    }
}
