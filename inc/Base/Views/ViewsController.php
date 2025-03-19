<?php
namespace AM\Scheduler\Base\Views;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Configs\Config;

class ViewsController
{
    use Singleton;

    public static function loadTemplate(
        string $template_file_path,
        mixed $data = []
    ): void {
        $template =
            Config::getInstance()->path->getViewsPath() . $template_file_path;

        if (!file_exists($template)) {
            throw new \Exception(
                "plese create content template file {$template}"
            );
        } else {
            require $template;
        }
    }
}
