<?php
/** TODO: replace Event controller with task controller when it will be done */
use AM\Scheduler\Event\Controllers\EventController;
use AM\Scheduler\Base\Views\ViewsController;
?>
<div class="wrap ams-screen ams-screen--<?php echo esc_attr(
    ViewsController::getScreen()->value
); ?>">

<?php ViewsController::loadTemplate(
    "/template-parts/components/headers/list-header.php",
    [
        "entity" => EventController::getInstance(),
    ]
); ?>
</div>
