<?php
namespace MHS\Base\Views;

use MHS\Base\Traits\Singleton;

class ViewsController
{
    use Singleton;

    public static function loadTemplate(
        string $template_file_path,
        mixed $data = []
    ): void {
        $template = MHS_PLUGIN_VIEWS_PATH . $template_file_path;

        if (!file_exists($template)) {
            throw new \Exception(
                "plese create content template file {$template}"
            );
        } else {
            require $template;
        }
    }
}
