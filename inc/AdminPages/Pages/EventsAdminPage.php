<?php
namespace AM\Scheduler\AdminPages\Pages;

use AM\Scheduler\AdminPages\Abstractions\AbstractPage;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Views\ViewsController;

class EventsAdminPage extends AbstractPage
{
    use Singleton;

    private function __construct(string $slug)
    {
        $this->slug = $slug;

        add_action(
            "admin_menu",
            fn() => add_menu_page(
                __("Events", "ams"),
                __("Scheduler", "ams"),
                "manage_options",
                $this->slug,
                [$this, "callback"],
                "dashicons-calendar",
                "5.8312365"
            )
        );
    }

    public function callback(): void
    {
        ViewsController::loadTemplate("/admin/events/content.php");
    }
}
