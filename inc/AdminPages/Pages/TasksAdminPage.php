<?php
namespace MHS\AdminPages\Pages;

use MHS\AdminPages\Abstractions\AbstractPage;
use MHS\Base\Traits\Singleton;
use MHS\Base\Views\ViewsController;

class TasksAdminPage extends AbstractPage
{
    use Singleton;

    private function __construct(string $slug)
    {
        $this->slug = "{$slug}_tasks";
        $this->parent_slug = $slug;

        add_action(
            "admin_menu",
            fn() => add_submenu_page(
                $this->parent_slug,
                __("Tasks", "mhs"),
                __("Tasks", "mhs"),
                "manage_options",
                $this->slug,
                [$this, "callback"],
                "5.8332365"
            )
        );
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/tasks/content.php");
    }
}
