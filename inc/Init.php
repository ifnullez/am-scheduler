<?php

namespace AM\Scheduler;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\AdminPages\AdminPagesLoader;
use AM\Scheduler\Api\ApiLoader;
use AM\Scheduler\Entities\EntitiesLoader;
use AM\Scheduler\Runner\SchedulerRunner;

final class Init
{
    use Singleton;

    private EntitiesLoader $entities;
    private AdminPagesLoader $admin_pages;
    private SchedulerRunner $runner;
    private ApiLoader $api;

    private function __construct()
    {
        $this->entities = EntitiesLoader::getInstance();
        $this->admin_pages = AdminPagesLoader::getInstance();
        $this->runner = SchedulerRunner::getInstance();
        $this->api = ApiLoader::getInstance();

        // load scripts and styles
        add_action("wp_enqueue_scripts", [$this, "public_scripts"]);
        add_action("admin_enqueue_scripts", [$this, "admin_scripts"]);
    }

    public function public_scripts(): void
    {
        wp_enqueue_script(
            "mh-scheduler-public-script",
            MHS_PLUGIN_ASSETS_URL . "/dist/scripts/mh-scheduler-main.js",
            [],
            MHS_PLUGIN_VERSION,
            true
        );
        wp_enqueue_style(
            "mh-scheduler-public-style",
            MHS_PLUGIN_ASSETS_URL . "/dist/styles/mh-scheduler-main.css",
            [],
            MHS_PLUGIN_VERSION
        );
    }

    public function admin_scripts(): void
    {
        wp_enqueue_script(
            "mh-scheduler-admin-script",
            MHS_PLUGIN_ASSETS_URL . "/dist/scripts/mh-scheduler-admin-main.js",
            [],
            MHS_PLUGIN_VERSION,
            true
        );
        wp_enqueue_style(
            "mh-scheduler-admin-styles",
            MHS_PLUGIN_ASSETS_URL . "/dist/styles/mh-scheduler-admin-main.css",
            [],
            MHS_PLUGIN_VERSION
        );
    }
}
