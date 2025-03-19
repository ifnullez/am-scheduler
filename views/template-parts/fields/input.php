<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */
$type = $data["type"] ?? "text";
$placeholder = $data["placeholder"] ?? "";
$label = $data["label"] ?? "";
$name = $data["name"] ?? null;
$id = $data["id"] ?? $name;
$value = $data["value"] ?? "";
$attributes = $data["attributes"] ?? "";
$info = $data["info"] ?? null;
$is_selectable = in_array($type, ["radio", "checkbox"])
    ? "input--selectable"
    : "input";

if ($name):
    if ($type !== "hidden"): ?>
    <div class="input-wrapper input__<?php echo esc_attr(
        $name
    ); ?> <?php echo esc_attr($is_selectable); ?>">
        <?php if ($label): ?>
            <label for="<?php echo esc_attr($id); ?>">
                <?php echo esc_html__($label, "ams"); ?>
            </label>
        <?php endif; ?>

        <?php if ($info): ?>
            <small><?php echo esc_html__($info, "ams"); ?></small>
        <?php endif; ?>
<?php endif; ?>

    <input
        type="<?php echo esc_attr($type); ?>"
        id="<?php echo esc_attr($id); ?>"
        name="<?php echo esc_attr($name); ?>"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        value="<?php echo esc_attr($value); ?>"
        <?php echo $attributes; ?>
    >

<?php if ($type !== "hidden"): ?>
    </div>
<?php endif;
endif; ?>
