<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */

use AM\Scheduler\Events\Models\EventsModel;

$id = !empty($data["item"]->id) ? $data["item"]->id : null;

if (EventsModel::getInstance()->delete($id)) { ?>
<h2>
    <?php _e("Event are deleted!", "ams"); ?>
</h2>
    <?php } else { ?>
     <h2>
         <?php _e(
             "Something get wrong when trying to delete. Please try again later!",
             "ams"
         ); ?>
     </h2>
<?php }
