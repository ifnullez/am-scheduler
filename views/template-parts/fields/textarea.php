<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */

$placeholder = $data["placeholder"] ?? "";
$label = $data["label"] ?? "";
$name = $data["name"] ?? null;
$id = $data["id"] ?? $name;
$value = $data["value"] ?? "";
$attributes = $data["attributes"] ?? "";
$info = $data["info"] ?? null;

if ($name): ?>
    <div class="input-wrapper textarea__<?php echo esc_attr($name); ?>">
        <?php if ($label): ?>
            <label for="<?php echo esc_attr($id); ?>">
                <?php echo esc_html__($label, "ams"); ?>
            </label>
        <?php endif; ?>

        <?php if ($info): ?>
            <small><?php echo esc_html__($info, "ams"); ?></small>
        <?php endif; ?>

        <textarea
            id="<?php echo esc_attr($id); ?>"
            name="<?php echo esc_attr($name); ?>"
            placeholder="<?php echo esc_attr($placeholder); ?>"
            <?php echo $attributes; ?>
        ><?php echo esc_textarea($value); ?></textarea>
    </div>
<?php endif; ?>
