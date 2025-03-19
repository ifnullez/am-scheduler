<?php
/**
 * @var array<string, mixed> $data Result of BaseModel::getAllAsOptionsArray()
 */

use AM\Scheduler\Base\Helpers\StaticHelper;

$label = $data["label"] ?? "";
$name = $data["name"] ?? null;
$id = $data["id"] ?? $name;
$options = $data["options"] ?? [];
$attributes = $data["attributes"] ?? "";
$selected_option = $data["value"] ?? null;

if ($name): ?>
    <div class="input-wrapper select__<?php echo esc_attr($name); ?>">
        <?php if ($label): ?>
            <label for="<?php echo esc_attr($id); ?>">
                <?php echo esc_html__($label, "ams"); ?>
            </label>
        <?php endif; ?>

        <select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr(
    $name
); ?>" <?php echo $attributes; ?>>
            <option value="" <?php selected(empty($selected_option)); ?>>
                <?php esc_html_e("Select an option", "ams"); ?>
            </option>

            <?php foreach ($options as $option_value => $option_title): ?>
                <option value="<?php echo esc_attr($option_value); ?>"
                    <?php selected(
                        StaticHelper::checkSelectedValue(
                            $option_value,
                            $selected_option
                        )
                    ); ?>>
                    <?php echo esc_html($option_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>
