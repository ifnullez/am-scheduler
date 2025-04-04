<?php

/**
 * Path for the plugin
 */

namespace AM\Scheduler\Base\Configs\Parts;

use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Base\Traits\Singleton;

class Path
{
    use Singleton;

    private Config $config;
    private ?string $root_file;
    private ?string $root_dir;
    private ?string $theme_dir;

    private function __construct(Config $config)
    {
        // root config
        $this->config = $config;
        $this->root_file = $config->root_file;
        $this->root_dir = $config->root_dir;
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
        return "{$this->theme_dir}/plugins/am/{$this->config->base_path}";
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
