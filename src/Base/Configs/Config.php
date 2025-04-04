<?php

/**
 * Main plugin config class
 */

namespace AM\Scheduler\Base\Configs;

use AM\Scheduler\Base\Configs\Parts\{Path, Uri};
use AM\Scheduler\Base\Helpers\StrHelper;
use AM\Scheduler\Base\Traits\Singleton;

class Config
{
    use Singleton;

    private ?Path $path;
    private ?Uri $uri;
    private ?string $root_file;
    private ?string $root_dir;
    private ?string $base_path;
    private ?string $namespace_prefix = "AM";

    private function __construct(?string $root_file, ?string $root_dir)
    {
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;
        $base_name = $this->getDataFromPluginDefinitionComment("Plugin Name");
        $this->base_path = !empty($base_name)
            ? (new StrHelper($base_name))
                ->stripEmojis()
                ->dropWords($this->namespace_prefix)
                ->stripSpacesAround()
                ->toLower()
            : null;

        if (!empty($this->root_file) && !empty($this->root_dir)) {
            $this->path = Path::getInstance($this);
            $this->uri = Uri::getInstance($this);
        }
    }

    public function getRootFile(): ?string
    {
        return $this->root_file;
    }

    public function getVersion(): ?string
    {
        return $this->root_file
            ? $this->getDataFromPluginDefinitionComment("Version")
            : null;
    }

    public function getDataFromPluginDefinitionComment(string $param): mixed
    {
        return $this->root_file
            ? get_file_data($this->root_file, ["param" => $param])["param"]
            : null;
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
