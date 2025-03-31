<?php
namespace AM\Scheduler\Events;

use AM\Scheduler\Base\Helpers\ObjectHelper;

// use AM\Scheduler\Base\Entities\AbstractEntity;

class Event
{
    public $id;
    public $title;
    public $event_date;
    public $location;
    public $description;
    /**
     * @param array<int,mixed> $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $helper = new ObjectHelper($data, $this);
            $helper->populateToSelf();
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function getIdentifier(): string
    {
        return "Event #{$this->id}";
    }
}
