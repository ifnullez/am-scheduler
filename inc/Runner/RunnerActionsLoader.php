<?php
namespace MHS\Runner;

use MHS\Base\Traits\Singleton;
use MHS\Runner\Actions\{RunnerCheckGroupAmountAction};

class RunnerActionsLoader
{
    use Singleton;

    protected array $actions = [];

    private function __construct()
    {
        $this->actions = [
            RunnerCheckGroupAmountAction::getInstance()
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
