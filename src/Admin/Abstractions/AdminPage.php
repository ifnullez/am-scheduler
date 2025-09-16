<?php
namespace AM\Scheduler\Admin\Abstractions;

use AM\Scheduler\Admin\Interfaces\AdminPageInterface;
use AM\Scheduler\Admin\Traits\AdminPageTrait;
use AM\Scheduler\Utils\Traits\{GetterTrait};
use Exception;

abstract class AdminPage implements AdminPageInterface
{
    use GetterTrait, AdminPageTrait;

    public function callback(): void
    {
        // dump($this->parent);
        echo new Exception(
            "No method named as <b>callback</b> provided for admin page content in",
            404
        );
    }
}
