<?php
namespace MHS\Rrule;

use MHS\Base\Traits\Singleton;
use MHS\Rrule\Actions\RruleSendPointsMembersAction;

class RruleActionsLoader
{
    use Singleton;

    protected array $actions = [];

    private function __construct()
    {
        $this->actions = [
            RruleSendPointsMembersAction::getInstance()
                ->action => RruleSendPointsMembersAction::getInstance(),
        ];
    }

    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
