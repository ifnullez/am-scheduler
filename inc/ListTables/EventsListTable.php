<?php

namespace AM\Scheduler\ListTables;

use AM\Scheduler\Events\Models\EventsModel;
use WP_List_Table;

class EventsListTable extends WP_List_Table
{
    private $table_data;

    public function get_columns()
    {
        $columns = [
            "cb" => '<input type="checkbox" />',
            "id" => __("ID", "ams"),
            "title" => __("Title", "ams"),
            "description" => __("Description", "ams"),
            "task_id" => __("Task", "ams"),
            "created_at" => __("Created At", "ams"),
            // "starting_at" => __("Starting At", "ams"),
            // "next_series_at" => __("Next Series Date", "ams"),
        ];
        return $columns;
    }

    public function prepare_items()
    {
        //data
        $this->process_bulk_action();
        if (isset($_POST["s"])) {
            $this->table_data = $this->get_table_data($_POST["s"]);
        } else {
            $this->table_data = $this->get_table_data();
        }

        $columns = $this->get_columns();
        $hidden = is_array(
            get_user_meta(
                get_current_user_id(),
                "managetoplevel_page_supporthost_list_tablecolumnshidden",
                true
            )
        )
            ? get_user_meta(
                get_current_user_id(),
                "managetoplevel_page_supporthost_list_tablecolumnshidden",
                true
            )
            : [];
        $sortable = $this->get_sortable_columns();
        $primary = "id";
        $this->_column_headers = [$columns, $hidden, $sortable, $primary];

        if (!empty($this->table_data)) {
            usort($this->table_data, [&$this, "usort_reorder"]);

            /* pagination */
            $per_page = $this->get_items_per_page("elements_per_page", 10);
            $current_page = $this->get_pagenum();
            $total_items = count($this->table_data ?? []);

            $this->table_data = array_slice(
                $this->table_data,
                ($current_page - 1) * $per_page,
                $per_page
            );

            $this->set_pagination_args([
                "total_items" => $total_items, // total number of items
                "per_page" => $per_page, // items to show on a page
                "total_pages" => ceil($total_items / $per_page), // use ceil to round up
            ]);
        }

        $this->items = $this->table_data;
    }

    private function get_table_data(string $search = ""): ?array
    {
        $event = EventsModel::getInstance();

        if (!empty($search)) {
            return $event->findWhere(
                ["id", "title", "description", "task_id"],
                $search
            );
        } else {
            return $event->getAll();
        }
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case "id":
            case "title":
            case "description":
            case "task":
            case "created_at":
            case "starting_at":
            case "next_series_at":
            default:
                return $item[$column_name];
        }
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="element[]" value="%s" />',
            $item["id"]
        );
    }

    protected function get_sortable_columns(): ?array
    {
        $sortable_columns = [
            "title" => ["title", false],
            "created_at" => ["created_at", false],
            "task_id" => ["task_id", true],
        ];
        return $sortable_columns;
    }

    public function usort_reorder($a, $b): ?string
    {
        // If no sort, default to user_login
        $orderby = !empty($_GET["orderby"]) ? $_GET["orderby"] : "id";

        // If no order, default to asc
        $order = !empty($_GET["order"]) ? $_GET["order"] : "asc";

        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);

        // Send final sort direction to usort
        return $order === "asc" ? $result : -$result;
    }

    public function column_id($item)
    {
        $actions = [
            "edit" => sprintf(
                '<a href="?page=%s&action=%s&element=%s">' .
                    __("Edit", "ams") .
                    "</a>",
                $_REQUEST["page"],
                "edit",
                $item["id"]
            ),
            "delete" => sprintf(
                '<a href="?page=%s&action=%s&element=%s">' .
                    __("Delete", "ams") .
                    "</a>",
                $_REQUEST["page"],
                "delete",
                $item["id"]
            ),
        ];

        return sprintf('%1$s %2$s', $item["id"], $this->row_actions($actions));
    }

    public function get_bulk_actions()
    {
        $actions = [
            "delete" => __("Delete", "ams"),
        ];
        return $actions;
    }

    public function process_bulk_action()
    {
        $ids = isset($_REQUEST["element"]) ? $_REQUEST["element"] : [];
        if (!empty($ids)) {
            switch ($this->current_action()) {
                case "delete":
                    EventsModel::getInstance()->batchDelete($ids);
                    break;
            }
        }
    }
}
