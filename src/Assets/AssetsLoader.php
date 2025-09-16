<?php

namespace AM\Scheduler\Assets;

use AM\Scheduler\Assets\Asset;
use AM\Scheduler\Assets\Localize\{PublicLocalize, AdminLocalize};
use AM\Scheduler\Utils\Traits\Singleton;

class AssetsLoader
{
    use Singleton;

    private string $i18nName = "amsI18n";

    private function __construct()
    {
        add_action("wp_enqueue_scripts", [$this, "public_scripts"]);
        add_action("admin_enqueue_scripts", [$this, "admin_scripts"]);
    }

    public function public_scripts(): void
    {
        (new Asset("/dist/scripts/ams-scheduler-main.js"))
            ->setI18nObjectName($this->i18nName)
            ->localize(PublicLocalize::getInstance()->i18n)
            ->load();
        (new Asset("/dist/styles/ams-scheduler-main.css"))->load();
    }

    public function admin_scripts(): void
    {
        (new Asset("/dist/scripts/ams-scheduler-admin-main.js"))
            ->setI18nObjectName($this->i18nName)
            ->localize(AdminLocalize::getInstance()->i18n)
            ->load();
        (new Asset("/dist/styles/ams-scheduler-admin-main.css"))->load();
    }
}
