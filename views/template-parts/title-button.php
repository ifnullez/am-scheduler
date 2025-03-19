<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */
?>
<div class="mh-admin__events wrap">
    <h1 class="mh-admin__events--title">
        <?php echo empty($data["title"])
            ? get_admin_page_title()
            : $data["title"]; ?>
    </h1>
    <a href="<?php echo $data["controller"]::getInstance()->rootPageUrl() .
        "&action=new"; ?>" class="mh-button-admin mh-button-admin--blue mh-admin__events--new">
        <?php _e("Add New", "ams"); ?>
    </a>
</div>
