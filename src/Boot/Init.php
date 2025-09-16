<?php

namespace AM\Scheduler\Boot;

use AM\Scheduler\Admin\AdminLoader;
use AM\Scheduler\Assets\AssetsLoader;
use AM\Scheduler\Utils\PluginMeta\Env;
use AM\Scheduler\Boot\Activation\OnActivate;
use AM\Scheduler\Boot\Deactivation\OnDeactivate;
// use AM\Scheduler\Boot\Loaders\Main;
use AM\Scheduler\Utils\Traits\Singleton;

final class Init
{
    use Singleton;

    public array $loaders = [];

    private function __construct()
    {
        // Boot plugin classes which should to be loaded on init
        $this->loaders = [
            AssetsLoader::getInstance(),
            AdminLoader::getInstance(),
        ];

        // On Plugin Activation
        register_activation_hook(Env::getInstance()->path->getRootFile(), [
            OnActivate::class,
            "getInstance",
        ]);

        // On Plugin Deactivation
        register_deactivation_hook(Env::getInstance()->path->getRootFile(), [
            OnDeactivate::class,
            "getInstance",
        ]);
    }
}
