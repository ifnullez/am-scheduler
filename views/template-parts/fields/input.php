<?php
$type = !empty($data["type"]) ? $data["type"] : "text";
$placeholder = !empty($data["placeholder"]) ? $data["placeholder"] : "";
$label = !empty($data["label"]) ? $data["label"] : "";
$name = !empty($data["name"]) ? $data["name"] : null;
$id = !empty($data["id"]) ? $data["id"] : $name;
$value = !empty($data["value"]) ? $data["value"] : "";
$attributes = !empty($data["attributes"]) ? $data["attributes"] : null;
$checkbox_or_radio =
    $type == "radio" || $type == "checkbox" ? "input--selectable" : "input";
$info = !empty($data["info"]) ? $data["info"] : null;
?>
<?php if (!empty($name)): ?>
    <?php if ($type !== "hidden"): ?>
        <div class="input-wrapper input__<?php echo "{$name}"; ?> <?php echo $checkbox_or_radio; ?>">
            <?php if (!empty($label)): ?>
                <label for="<?php echo $id; ?>">
                    <?php _e($label, "ams"); ?>
                </label>
        <?php endif;endif; ?>

        <?php if (!empty($info)): ?>
            <small><?php echo _e($info, "ams"); ?></small>
        <?php endif; ?>
        <input type="<?php echo $type; ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>" value="<?php echo $value; ?>" <?php echo $attributes; ?>>
        <?php if ($type !== "hidden"): ?>
        </div>
<?php endif;endif;
