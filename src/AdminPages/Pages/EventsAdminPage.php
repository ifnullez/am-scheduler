<?php
namespace AM\Scheduler\AdminPages\Pages;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;
use AM\Scheduler\AdminPages\Abstractions\AbstractAdminPage;

class EventsAdminPage extends AbstractAdminPage
{
    use Singleton;

    private function __construct(string $menu_slug)
    {
        $this->menu_slug = $menu_slug;
        $this->slug = "{$this->menu_slug}-events";

        add_action("admin_menu", function () {
            add_menu_page(
                __("Events", "ams"),
                __("Scheduler", "ams"),
                "manage_options",
                $this->menu_slug,
                [$this, "callback"],
                "dashicons-calendar",
                $this->menu_position
            );

            add_submenu_page(
                $this->menu_slug,
                __("Events", "ams"),
                __("Events", "ams"),
                "manage_options",
                $this->slug,
                [$this, "callback"]
            );

            remove_submenu_page($this->menu_slug, $this->menu_slug);
        });
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/events/content.php");
    }
}
