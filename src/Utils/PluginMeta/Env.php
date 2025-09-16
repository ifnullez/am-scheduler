<?php

namespace AM\Scheduler\Utils\PluginMeta;

use AM\Scheduler\Utils\Helpers\StrHelper;
use AM\Scheduler\Utils\PluginMeta\Services\{Path, Uri};
use AM\Scheduler\Utils\Traits\{GetterTrait, Singleton};

class Env
{
    use Singleton;
    use GetterTrait;

    private ?Path $path = null;
    private ?Uri $uri = null;
    private ?string $root_file;
    private ?string $root_dir;
    private ?string $base_path = null;
    private ?string $prefix = null;

    private function __construct(?string $root_file, ?string $root_dir)
    {
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;

        if ($this->hasValidRoot()) {
            $this->prefix = $this->getPluginMeta("Text Domain");
            $this->base_path = $this->buildBasePath();

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
        return $this->hasValidRoot() ? $this->getPluginMeta("Version") : null;
    }

    public function getDataFromPluginDefinitionComment(string $param): mixed
    {
        return $this->hasValidRoot()
            ? get_file_data($this->root_file, ["param" => $param])["param"]
            : null;
    }

    private function hasValidRoot(): bool
    {
        return !empty($this->root_file) && !empty($this->root_dir);
    }

    private function getPluginMeta(string $key): ?string
    {
        $data = get_file_data($this->root_file, ["key" => $key]);
        return $data["key"] ?? null;
    }

    private function buildBasePath(): ?string
    {
        $pluginName = $this->getPluginMeta("Plugin Name");

        if (empty($pluginName)) {
            return null;
        }

        return (new StrHelper($pluginName))
            ->stripEmojis()
            ->dropWords(["AM"])
            ->stripSpacesAround()
            ->toLower()
            ->get();
    }
}
