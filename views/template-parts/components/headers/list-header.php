<?php use AM\Scheduler\Base\Views\ViewsController; ?>

<div class="ams-header">
    <h1 class="wp-heading-inline">
        <?php echo ViewsController::getScreenTitle(); ?>
    </h1>
    <?php ViewsController::loadTemplate(
        "/template-parts/components/buttons/action-new-button.php",
        [
            "entity" => $entity,
        ]
    ); ?>
</div>
