<?php
use AM\Scheduler\Base\Views\ViewsController; ?>

<h1 class="wp-heading-inline">
    <?php echo esc_html(get_admin_page_title()); ?>
</h1>
<?php ViewsController::loadTemplate(
    "/template-parts/components/action-buttons/action-new-button.php",
    [
        "entity" => $entity,
    ]
);
