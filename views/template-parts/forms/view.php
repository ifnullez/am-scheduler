<div class="wrap">
    <form method="post">
        <?php
        $data["table"]->prepare_items();
        $data["table"]->search_box("search", "search_id");
        $data["table"]->display();
        ?>
    </form>
</div>
