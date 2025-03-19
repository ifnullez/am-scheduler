<?php

use AM\Scheduler\Series\Controllers\SeriesController; ?>
<div class="wrap mh-admin mh-admin__content mh-scheduler">
    <form class="mh-scheduler__form" method="POST" enctype="multipart/form-data" novalidate>
        <div class="mh-scheduler__form--content">
            <?php SeriesController::getInstance()->getContent(); ?>
        </div>
        <aside class="mh-scheduler__form--sidebar">
            <button type="submit" class="mh-button-admin mh-button-admin--green">
                <?php _e("Save", "ams"); ?>
            </button>
            <?php SeriesController::getInstance()->getSidebar(); ?>
        </aside>
    </form>
</div>
