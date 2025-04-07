<?php
namespace AM\Scheduler\Event\Controllers;

use AM\Scheduler\Admin\Pages\EventsAdminPage;
// use AM\Scheduler\Base\Abstractions\AbstractItemController;
use AM\Scheduler\Base\Traits\Singleton;
// use AM\Scheduler\Base\Views\ViewsController;
// use AM\Scheduler\Entities\Event;
// use AM\Scheduler\Models\EventModel;
// use AM\Scheduler\ListTables\EventsListTable;
// use AM\Scheduler\Events\Fields\{EditableFields, SidebarFields};
use AM\Scheduler\Rrule\Controllers\RruleOccurences;
// use AM\Scheduler\Models\SeriesModel;
// use AM\Scheduler\Models\TasksModel;

class EventController
{
    use Singleton;

    // private EventModel $eventModel;
    // private SeriesModel $seriesModel;
    // private TasksModel $tasksModel;
    private ?string $slug;

    private function __construct()
    {
        $this->slug = EventsAdminPage::getInstance()->slug;

        // $this->table_name = "events"; // Matches EventModel table name
        // $this->eventModel = new EventModel();
        // $this->seriesModel = new SeriesModel();
        // $this->tasksModel = new TasksModel();
        // $this->onSave(); // Handle save on construction if POST data exists

        // // Fill fields for edit/new views
        // $this->editable_fields = EditableFields::getInstance([
        //     "slug" => $this->slug,
        //     "table_name" => $this->table_name,
        // ])::getFields();
        // $this->sidebar_fields = SidebarFields::getInstance([
        //     "slug" => $this->slug,
        //     "table_name" => $this->table_name,
        // ])::getFields();
    }

    // public function __get(string $name): mixed
    // {
    //     if (property_exists($this, $name)) {
    //         return $this->$name;
    //     }
    //     return null;
    // }

    public function rootPageUrl(): string
    {
        return admin_url("/admin.php?page={$this->slug}");
    }

    public function list(): void
    {
        // ViewsController::loadTemplate("/template-parts/title-button.php", [
        //     "controller" => $this::getInstance(),
        // ]);
        // ViewsController::loadTemplate("/template-parts/forms/view.php", [
        //     "table" => new EventsListTable(),
        //     "controller" => $this::getInstance(),
        // ]);
    }

    public function delete(int $id): void
    {
        // if ($this->eventModel->delete($id)) {
        //     wp_redirect($this->rootPageUrl());
        //     exit();
        // }
    }

    public function edit(int $id): void
    {
        // $event = $this->eventModel->findBy("id", $id);
        // ViewsController::loadTemplate("/template-parts/title-button.php", [
        //     "controller" => $this::getInstance(),
        //     "title" => "Edit Event",
        // ]);
        // ViewsController::loadTemplate("/admin/events/actions/edit.php", [
        //     "item" => $event,
        //     "controller" => $this::getInstance(),
        // ]);
    }

    public function new(): void
    {
        // ViewsController::loadTemplate("/template-parts/title-button.php", [
        //     "controller" => $this::getInstance(),
        //     "title" => "New Event",
        // ]);
        // ViewsController::loadTemplate("/admin/events/actions/new.php", [
        //     "controller" => $this::getInstance(),
        // ]);
    }
    /**
     * @param array<int,mixed> $data
     */
    public function onSave(array $data = [], bool $return_item_id = false): ?int
    {
        // $data = !empty($data) ? $data : $_POST;
        // $new_id = null;

        // if (
        //     empty($data["slug_name"]) ||
        //     $data["slug_name"] !== "mh_scheduler"
        // ) {
        //     return null; // Invalid request
        // }

        // // Clean up data
        // unset($data["action"], $data["slug_name"]);

        // // Handle recurrence
        // $rrule = new RruleOccurences($data);
        // $data_for_save = $this->prepareSaveData($data, $rrule);

        // // Save or update event
        // if (empty($data["id"])) {
        //     $newEvent = $this->eventModel->create($data_for_save);
        //     $new_id = $newEvent->id;
        // } else {
        //     $this->eventModel->update((int) $data["id"], $data_for_save);
        //     $new_id = (int) $data["id"];
        // }

        // // Handle related series and tasks
        // $this->saveRelatedRecords($new_id, $data_for_save, $rrule);

        // if (!$return_item_id) {
        //     wp_redirect($this->rootPageUrl());
        //     exit();
        // }
        // return $new_id;
        return 0;
    }
    /**
     * @param array<int,mixed> $data
     */
    private function prepareSaveData(array $data, RruleOccurences $rrule): array
    {
        // $json_fields = ["members_ids", "meta"];
        // $save_data = [];

        // foreach ($data as $key => $value) {
        //     if (in_array($key, $json_fields)) {
        //         $save_data[$key] = !empty($value) ? json_encode($value) : null;
        //     } else {
        //         $save_data[$key] = $value;
        //     }
        // }

        // $save_data["pattern"] = $rrule->pattern;
        // $save_data["start_date"] = $rrule->rrule->startDate->format("c");
        // $save_data["until"] = $rrule->rrule->until->format("c");

        // return $save_data;
        return [];
    }
    /**
     * @param array<int,mixed> $data
     */
    private function saveRelatedRecords(
        int $event_id,
        array $data,
        RruleOccurences $rrule
    ): void {
        // $task = !empty($data["task_id"])
        //     ? $this->tasksModel->findBy("id", $data["task_id"])
        //     : null;
        // if (!$task || empty($rrule->rrule->occurrences)) {
        //     return;
        // }

        // $group_id = $task->group_id ?? null;
        // $members_ids = $task->members_ids ?? null;

        // $values = [];
        // foreach ($rrule->rrule->occurrences as $occurrence) {
        //     $starting_at = $occurrence->format("c");
        //     $values[] = [
        //         "title" => "Series for: {$data["title"]}",
        //         "event_id" => $event_id,
        //         "members_ids" => $members_ids,
        //         "group_id" => $group_id,
        //         "task_id" => $task->id,
        //         "author_id" => $data["author_id"] ?? get_current_user_id(),
        //         "starting_at" => $starting_at,
        //         "amount" => $task->amount ?? 0,
        //         "action" => $task->action ?? "",
        //         "message" => $task->message ?? "",
        //         "reason" => $task->reason ?? "",
        //     ];
        // }

        // // Handle series updates
        // $existingSeries = $this->seriesModel->findBy(
        //     "event_id",
        //     (string) $event_id
        // );
        // if ($existingSeries) {
        //     $seriesIds = array_column($existingSeries, "id");
        //     $this->seriesModel->deleteMultiple($seriesIds); // Assuming deleteMultiple() is added
        // }
        // $this->seriesModel->createMultiple($values); // Assuming createMultiple() is added
    }
}
