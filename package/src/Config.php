<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

final class Config
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Directory
     */
    private $tmp;

    /**
     * @var Output
     */
    private $output;

    /**
     * @var Directory[]
     */
    private $directories;

    public function __construct(Database $database, Directory $tmp, Output $output, Directory... $directories)
    {
        $this->database    = $database;
        $this->tmp         = $tmp;
        $this->output      = $output;
        $this->directories = $directories;
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * @return Directory[]
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @return Directory
     */
    public function getTmp(): Directory
    {
        return $this->tmp;
    }

    /**
     * @return Output
     */
    public function getOutput(): Output
    {
        return $this->output;
    }
}
