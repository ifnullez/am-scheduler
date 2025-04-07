<div class="wrap">
<?php
use AM\Scheduler\Event\Controllers\EventController;
use AM\Scheduler\Base\Views\ViewsController;

ViewsController::loadTemplate(
    "/template-parts/components/headers/list-header.php",
    [
        "entity" => EventController::getInstance(),
    ]
);

// dump(EventsController::getInstance());
// EventsController::getInstance()->conditionalDisplay();
?>
</div>
