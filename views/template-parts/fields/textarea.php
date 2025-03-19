<?php
$placeholder = !empty($data["placeholder"]) ? $data["placeholder"] : "";
$label = !empty($data["label"]) ? $data["label"] : "";
$name = !empty($data["name"]) ? $data["name"] : null;
$id = !empty($data["id"]) ? $data["id"] : $name;
$value = !empty($data["value"]) ? $data["value"] : "";
$attributes = !empty($data["attributes"]) ? $data["attributes"] : null;
$info = !empty($data["info"]) ? $data["info"] : null;
?>
<?php if (!empty($name)): ?>
    <div class="input-wrapper textarea__<?php echo "{$name}"; ?>">
        <?php if (!empty($label)): ?>
            <label for="<?php echo $id; ?>">
                <?php _e($label, "ams"); ?>
            </label>
        <?php endif; ?>
        <?php if (!empty($info)): ?>
            <small>
                <?php echo _e($info, "ams"); ?>
            </small>
        <?php endif; ?>
        <textarea id="<?php echo $id; ?>" name="<?php echo $name; ?>" placeholder="<?php echo $placeholder; ?>" <?php echo $attributes; ?>><?php echo $value; ?></textarea>
    </div>
<?php endif; ?>
