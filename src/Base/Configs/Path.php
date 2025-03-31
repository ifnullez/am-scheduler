<?php

/**
 * Path for the plugin
 */

namespace AM\Scheduler\Base\Configs;

use AM\Scheduler\Base\Traits\Singleton;

class Path
{
    use Singleton;

    private ?string $root_file;
    private ?string $root_dir;
    private ?string $theme_dir;

    private function __construct(?string $root_file, ?string $root_dir)
    {
        // plugin root file
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;
        $this->theme_dir = get_stylesheet_directory();
    }

    public function getRootPath(): ?string
    {
        return $this->root_dir;
    }

    public function getThemePath(): ?string
    {
        return $this->theme_dir;
    }

    public function getThemeViewsPath(): string
    {
        return "{$this->theme_dir}/plugins/am/scheduler";
    }

    public function getSrcPath(): string
    {
        return "{$this->root_dir}/src";
    }

    public function getViewsPath(): string
    {
        return "{$this->root_dir}/views";
    }

    public function getAssetsPath(?string $asset): string
    {
        return "{$this->root_dir}/assets{$asset}";
    }
}
