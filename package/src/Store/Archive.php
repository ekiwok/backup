<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup\Store;

use Ekiwok\WPBackup\Directory;
use Symfony\Component\Filesystem\Filesystem;

abstract class Archive
{
    protected $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    abstract public function getRealPath() : string;

    abstract public function addFile(string $fileRealPath) : void;

    abstract public function addDirectory(Directory $directory) : void;

    /**
     * @throws ArchiveOperationException
     */
    abstract public function compress() : void;

    public function mv(string $dst) : void
    {
        $this->fs->rename($this->getRealPath(), $dst, true);
    }
}
