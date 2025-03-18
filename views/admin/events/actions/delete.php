<?php
use MHS\Events\Models\EventsModel;

$id = !empty($data["item"]->id) ? $data["item"]->id : null;

if (EventsModel::getInstance()->delete($id)) { ?>
<h2>
    <?php _e("Event are deleted!", "mhs"); ?>
</h2>
    <?php } else { ?>
     <h2>
         <?php _e(
             "Something get wrong when trying to delete. Please try again later!",
             "mhs"
         ); ?>
     </h2>
<?php }
