<?php
use AM\Scheduler\Series\Models\SeriesModel;

$id = !empty($data["item"]->id) ? $data["item"]->id : null;

if (SeriesModel::getInstance()->delete($id)) { ?>
<h2>
    <?php _e("Series are deleted!", "ams"); ?>
</h2>
<?php } else { ?>
     <h2>
         <?php _e(
             "Something get wrong when trying to delete. Please try again later!",
             "ams"
         ); ?>
     </h2>
<?php }
