<?php
namespace AM\Scheduler\AdminPages\Pages;

use AM\Scheduler\AdminPages\Abstractions\AbstractAdminPage;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;

class TasksAdminPage extends AbstractAdminPage
{
    use Singleton;

    private function __construct(string $menu_slug)
    {
        $this->menu_slug = $menu_slug;
        $this->slug = "{$this->menu_slug}-tasks";

        add_action(
            "admin_menu",
            fn() => add_submenu_page(
                $this->menu_slug,
                __("Tasks", "ams"),
                __("Tasks", "ams"),
                "manage_options",
                $this->slug,
                [$this, "callback"],
                $this->menu_position
            )
        );
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/tasks/content.php");
    }
}
