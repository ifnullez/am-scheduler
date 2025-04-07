<?php
namespace AM\Scheduler\Admin\Pages;

use AM\Scheduler\Admin\Abstractions\AbstractAdminPage;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;

class SeriesAdminPage extends AbstractAdminPage
{
    use Singleton;

    private function __construct(?string $menu_slug)
    {
        $this->menu_slug = $menu_slug;
        $this->slug = "{$this->menu_slug}-series";

        add_action(
            "admin_menu",
            fn() => add_submenu_page(
                $this->menu_slug,
                __("Series", "ams"),
                __("Series", "ams"),
                "manage_options",
                $this->slug,
                [$this, "callback"],
                $this->menu_position
            )
        );
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/series/content.php");
    }
}
