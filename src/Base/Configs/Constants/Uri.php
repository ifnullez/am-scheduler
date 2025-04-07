<?php

/**
 * Path for the plugin
 */

namespace AM\Scheduler\Base\Configs\Constants;

use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Base\Traits\Singleton;

class Uri
{
    use Singleton;

    private Config $config;
    private ?string $root_file;
    private ?string $root_dir;

    private ?string $root_url;
    private ?string $theme_url;

    private function __construct(Config $config = null)
    {
        // root config
        $this->config = $config;
        // plugin root file
        $this->root_file = $this->config->root_file;
        $this->root_dir = $this->config->root_dir;

        $this->theme_url = get_stylesheet_directory_uri();
        $this->root_url = plugin_dir_url($this->config->root_file);
    }

    public function getAssetsUri(?string $asset): string
    {
        return esc_url("{$this->root_url}/assets/{$asset}");
    }

    public function getRootUrl(): ?string
    {
        return $this->root_url;
    }

    public function getThemeUrl(): ?string
    {
        return $this->theme_url;
    }

    public function getThemeViewsUrl(): string
    {
        return "{$this->theme_url}/plugins/am/{$this->config->base_path}";
    }

    public function getAssetsUrl(): string
    {
        return "{$this->root_url}assets";
    }
}
