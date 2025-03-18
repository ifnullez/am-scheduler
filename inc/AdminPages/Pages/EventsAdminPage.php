<?php
namespace MHS\AdminPages\Pages;

use MHS\AdminPages\Abstractions\AbstractPage;
use MHS\Base\Traits\Singleton;
use MHS\Base\Views\ViewsController;

class EventsAdminPage extends AbstractPage
{
    use Singleton;

    private function __construct(string $slug)
    {
        $this->slug = $slug;

        add_action(
            "admin_menu",
            fn() => add_menu_page(
                __("Events", "mhs"),
                __("Scheduler", "mhs"),
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
