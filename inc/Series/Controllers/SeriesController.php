<?php

namespace MHS\Series\Controllers;

use MHS\AdminPages\Pages\SeriesAdminPage;
use MHS\Base\Abstractions\AbstractItemController;
use MHS\Base\Traits\Singleton;
use MHS\Base\Views\ViewsController;
use MHS\Entities\Entity\SeriesEntity;
use MHS\Events\Event;
use MHS\Events\Models\EventsModel;
use MHS\Series\Models\SeriesModel;
use MHS\ListTables\SeriesListTable;
use MHS\Series\Fields\{EditableFields, SidebarFields};
use MHS\Series\Series;
use MHS\Tasks\Models\TasksModel;
use WP_REST_Request;

class SeriesController extends AbstractItemController
{
    use Singleton;

    private function __construct()
    {
        $this->slug = SeriesAdminPage::getInstance()->slug;
        $this->table_name = SeriesEntity::getInstance()->table_name;

        // fill the main content fields
        $this->editable_fields = EditableFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();

        // fill the sidebar fields
        $this->sidebar_fields = SidebarFields::getInstance([
            "slug" => $this->slug,
            "table_name" => $this->table_name,
        ])::getFields();

        $this->onSave();
    }

    public function editableFields(): void
    {
    }

    public function list(): void
    {
        ViewsController::loadTemplate("/template-parts/title-button.php", [
            "controller" => $this::getInstance(),
        ]);
        ViewsController::loadTemplate("/template-parts/forms/view.php", [
            "table" => new SeriesListTable(),
            "controller" => $this::getInstance(),
        ]);
    }

    public function delete(int $id): void
    {
        // currently we not maintain actions when we can't delete item
        if (SeriesModel::getInstance()->delete($id)) {
            wp_redirect($this->rootPageUrl(), 302);
            exit();
        }
    }

    public function edit(int $id): void
    {
        ViewsController::loadTemplate("/admin/series/actions/edit.php", [
            "item" => new Series($id),
            "controller" => $this::getInstance(),
        ]);
    }

    public function new(): void
    {
        ViewsController::loadTemplate("/admin/series/actions/new.php", [
            "controller" => $this::getInstance(),
        ]);
    }

    public function onSave(array $data = [], $return_item_id = false): ?int
    {
        $data = !empty($data) ? $data : $_POST;
        $new_id = null;
        if (
            !empty($data["slug_name"]) &&
            $data["slug_name"] === "mh_scheduler_series"
        ) {
            // unset not existing table fields
            unset($data["slug_name"]);

            $json_fields = ["members_ids", "meta"];
            $data_for_save_update = $this->toSaveUpdateArray(
                $data,
                $json_fields
            );
            if (empty($data["id"])) {
                // save data
                $new_id = SeriesModel::getInstance()->save($data_for_save_update);
            } else {
                SeriesModel::getInstance()->update($data_for_save_update);
            }
            if (!$return_item_id) {
                return exit(wp_redirect($this->rootPageUrl()));
            }
        }
        return $new_id;
    }

    public function updateData(
        array $field_and_values,
        array $where_values,
        string $where_key = "event_id"
    ): ?bool {
        if (!empty($field_and_values)) {
            return SeriesModel::getInstance()->batchUpdate(
                data: $field_and_values,
                where_key: $where_key,
                where_values: $where_values,
                operator: "IN"
            );
        }
    }

    public function bulkActions(WP_REST_Request $request): void
    {
        $rst = !empty($request->get_body_params()["selected"]) ? json_decode($request->get_body_params()["selected"]) : null;

        switch ($rst->selected_action) {
            case "remove_selected_series":
                $this->bulkActinsRemove($rst);
                break;
            default:
                wp_send_json([
                    "message" => "Nothing Selected!",
                    "request_data" => $rst
                ]);
                break;
        }
    }

    private function bulkActinsRemove(mixed $data): void
    {
        $series_items_ids = [];
        $events_ids = [];
        $tasks_ids = [];
        if (!empty($data->items)) {
            foreach ($data->items as $series_item_data) {
                $series_items_ids[] = $series_item_data->series_item_id;
                $events_ids[] = $series_item_data->event_id;
                $tasks_ids[] = $series_item_data->task_id;
            }

            $batchDelete = !empty($series_items_ids) ? SeriesModel::getInstance()->batchDelete($series_items_ids) : false;

            $events_to_delete = [];
            $tasks_to_delete = [];

            if (!empty($events_ids)) {
                foreach ($events_ids as $event_id) {
                    $findedSeries = SeriesModel::getInstance()->findBy(
                        "event_id",
                        $event_id
                    );
                    if (empty($findedSeries)) {
                        $event = new Event($event_id);
                        $events_to_delete[] = $event_id;
                        $tasks_to_delete[] = $event->task_id;
                    }
                }
                $batch_delete_events = !empty($events_to_delete) ? EventsModel::getInstance()->batchDelete($events_to_delete) : false;
                $batch_delete_tasks = !empty($tasks_to_delete) ? TasksModel::getInstance()->batchDelete($tasks_to_delete) : false;
            }


            if ($batchDelete) {
                wp_send_json_success([
                    "message" => "Successfully Deleted!",
                    "request_data" => $data
                ]);
            } else {
                wp_send_json_error([
                    "message" => "Something went wrong!",
                    "request_data" => $data
                ]);
            }
        }
        wp_send_json([
            "message" => "Nothing Selected!",
            "request_data" => $data
        ]);
    }
}
