<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

final class Directory
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string|null
     */
    private $optionalName;

    public function __construct(string $directory, string $optionalName = null)
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException(sprintf("%s is not a directory", $directory));
        }

        $this->directory = $directory;
        $this->optionalName = $optionalName;
    }

    public function __toString()
    {
        return $this->directory;
    }

    public function getOptionalNameOrBasePath() : string
    {
        return !is_null($this->optionalName)
            ? $this->optionalName
            : str_replace(dirname($this->directory), '', $this->directory);
    }
}
