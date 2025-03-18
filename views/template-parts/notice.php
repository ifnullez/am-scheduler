<?php if(!empty($data['notice'])){ ?>
    <div class="notice notice-<?php echo !empty($data['notice']['type']) ? $data['notice']['type'] : 'success'; ?> is-dismissible">
        <p>
            <?php echo !empty($data['notice']['message']) ? $data['notice']['message'] : 'Please provide message text!'; ?>
        </p>
    </div>
<?php } ?>
