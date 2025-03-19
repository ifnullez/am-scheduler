<?php

/**
 * Main plugin config class
 */

namespace AM\Scheduler\Base\Configs;

use AM\Scheduler\Base\Traits\Singleton;

class Config
{
    use Singleton;

    public ?Path $path;
    public ?Uri $uri;
    private ?string $root_file;
    private ?string $root_dir;

    private function __construct(?string $root_file, ?string $root_dir)
    {
        $this->root_file = $root_file;
        $this->root_dir = $root_dir;

        if (!empty($this->root_file) && !empty($this->root_dir)) {
            $this->path = Path::getInstance($this->root_file, $this->root_dir);
            $this->uri = Uri::getInstance($this->root_file, $this->root_dir);
        }
    }

    public function getRootFile(): ?string
    {
        return $this->root_file;
    }

    public function getVersion(): ?string
    {
        return $this->root_file
            ? get_file_data($this->root_file, ["Version" => "Version"])[
                "Version"
            ]
            : null;
    }
}
