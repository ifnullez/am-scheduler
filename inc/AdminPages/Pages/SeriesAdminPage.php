<?php
namespace AM\Scheduler\AdminPages\Pages;

use AM\Scheduler\AdminPages\Abstractions\AbstractPage;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;

class SeriesAdminPage extends AbstractPage
{
    use Singleton;

    private function __construct(string $slug)
    {
        $this->slug = "{$slug}_series";
        $this->parent_slug = $slug;

        add_action(
            "admin_menu",
            fn() => add_submenu_page(
                $this->parent_slug,
                __("Series", "ams"),
                __("Series", "ams"),
                "manage_options",
                $this->slug,
                [$this, "callback"],
                "5.8332365"
            )
        );
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/series/content.php");
    }
}
