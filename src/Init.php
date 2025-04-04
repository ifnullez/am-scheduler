<?php

namespace AM\Scheduler;

use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\AdminPages\AdminPagesLoader;
use AM\Scheduler\Entities\EntitiesLoader;
// use AM\Scheduler\Api\ApiLoader;
// use AM\Scheduler\Entities\EntitiesLoader;
// use AM\Scheduler\Runner\SchedulerRunner;

final class Init
{
    use Singleton;

    private ?ApiLoader $api;
    private ?EntitiesLoader $migrations;
    private AdminPagesLoader $admin_pages;
    // private SchedulerRunner $runner;
    // private ApiLoader $api;

    private function __construct()
    {
        $this->migrations = EntitiesLoader::getInstance();
        $this->api = ApiLoader::getInstance();
        // $this->entities = EntitiesLoader::getInstance();
        $this->admin_pages = AdminPagesLoader::getInstance();
        // $this->runner = SchedulerRunner::getInstance();

        // load scripts and styles
        add_action("wp_enqueue_scripts", [$this, "public_scripts"]);
        add_action("admin_enqueue_scripts", [$this, "admin_scripts"]);
    }

    public function public_scripts(): void
    {
        wp_enqueue_script(
            "ams-scheduler-public-script",
            Config::getInstance()->uri->getAssetsUri(
                "/dist/scripts/ams-scheduler-main.js"
            ),
            [],
            Config::getInstance()->getVersion(),
            true
        );
        wp_enqueue_style(
            "ams-scheduler-public-style",
            Config::getInstance()->uri->getAssetsUri(
                "/dist/styles/ams-scheduler-main.css"
            ),
            [],
            Config::getInstance()->getVersion()
        );
    }

    public function admin_scripts(): void
    {
        wp_enqueue_script(
            "ams-scheduler-admin-script",
            Config::getInstance()->uri->getAssetsUri(
                "/dist/scripts/ams-scheduler-admin-main.js"
            ),
            [],
            Config::getInstance()->getVersion(),
            true
        );
        wp_enqueue_style(
            "ams-scheduler-admin-styles",
            Config::getInstance()->uri->getAssetsUri(
                "/dist/styles/ams-scheduler-admin-main.css"
            ),
            [],
            Config::getInstance()->getVersion()
        );
    }
}
