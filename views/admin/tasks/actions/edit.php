<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */
use AM\Scheduler\Tasks\Controllers\TasksController; ?>
<div class="wrap mh-admin mh-admin__content mh-scheduler">
    <form class="mh-scheduler__form" method="POST" enctype="multipart/form-data" novalidate>
        <div class="mh-scheduler__form--content">
            <?php TasksController::getInstance()->getContent($data["item"]); ?>
        </div>
        <aside class="mh-scheduler__form--sidebar">
            <button type="submit" class="mh-button-admin mh-button-admin--green">
                <?php _e("Save", "ams"); ?>
            </button>
            <?php TasksController::getInstance()->getSidebar($data["item"]); ?>
        </aside>
    </form>
</div>
