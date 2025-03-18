<?php

use MHS\Base\Helpers\StaticHelper;

$label = !empty($data["label"]) ? $data["label"] : "";
$name = !empty($data["name"]) ? $data["name"] : null;
$id = !empty($data["id"]) ? $data["id"] : $name;
$options = !empty($data["options"]) ? $data["options"] : [];
$attributes = !empty($data["attributes"]) ? $data["attributes"] : null;
$selected_option = !empty($data["value"]) ? $data["value"] : null;
?>
<?php if (!empty($name)) : ?>
    <div class="input-wrapper select__<?php echo "{$name}"; ?>">
        <?php if (!empty($label)) : ?>
            <label for="<?php echo $id; ?>">
                <?php _e($label, "mhs"); ?>
            </label>
        <?php endif; ?>
        <select id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php echo $attributes; ?>>
            <?php if (!empty($options)) : ?>
                <option value="" <?php echo empty($selected_option) ? "selected" : ""; ?>>
                    <?php _e("Select an option", "mhs"); ?>
                </option>
                <?php foreach ($options as $option_value => $option_title) : ?>
                    <option value="<?php echo $option_value; ?>" <?php echo StaticHelper::checkSelectedValue($option_value, $selected_option) ? "selected" : ""; ?>>
                        <?php echo $option_title; ?>
                    </option>
            <?php
                endforeach;
            endif;
            ?>
        </select>
    </div>
<?php
endif;
