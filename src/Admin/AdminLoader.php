<?php

namespace AM\Scheduler\Admin;

use AM\Scheduler\Admin\Pages\MainPage;
use AM\Scheduler\Utils\Traits\Singleton;

class AdminLoader
{
    use Singleton;

    private readonly array $pages;
    private readonly string $pagesRoot;

    private function __construct()
    {
        // $this->pagesRoot = Env
        $this->pages = [MainPage::getInstance()];
    }
}
