<?php

namespace AM\Scheduler\Base\Views;

use AM\Scheduler\Base\Traits\Singleton;
use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Base\Helpers\StrHelper;
use RuntimeException;

class ViewsController
{
    use Singleton;

    /**
     * Loads and renders a template file with provided data
     *
     * @param string $templateFilePath Relative path to the template file
     * @param array $data Data to make available in the template
     * @throws RuntimeException If the template file doesn't exist
     */
    public static function loadTemplate(
        string $templateFilePath,
        array $data = []
    ): void {
        $fullPath = self::resolveTemplatePath($templateFilePath);

        if (!file_exists($fullPath)) {
            throw new RuntimeException("Template file not found: {$fullPath}");
        }

        // Extract data into the current scope
        extract($data, EXTR_SKIP);

        require $fullPath;
    }

    /**
     * Resolves the full path to a template file
     *
     * @param string $templateFilePath Relative template path
     * @return string Full absolute path
     */
    private static function resolveTemplatePath(
        string $templateFilePath
    ): string {
        return Config::getInstance()->path->getViewsPath() . $templateFilePath;
    }

    public static function getScreen(): ?ViewsScreenEnum
    {
        return ViewsScreenEnum::tryFrom(
            !empty($_GET["action"]) ? $_GET["action"] : ""
        ) ?? ViewsScreenEnum::LIST;
    }

    public static function getScreenTitle(): ?string
    {
        return self::getScreen()->name !== "LIST"
            ? (new StrHelper(esc_html(self::getScreen()->name)))
                    ->toLower()
                    ->capitalize() .
                    " " .
                    (new StrHelper(
                        esc_html(get_admin_page_title())
                    ))->stripRight("s")
            : esc_html(get_admin_page_title());
    }
}
