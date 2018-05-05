<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

final class ConfigBuilder
{
    const ENV_DB_PASS  = 'DB_PASS';
    const ENV_DB_HOST  = 'DB_HOST';
    const ENV_DB_PORT  = 'DB_PORT';
    const ENV_DB_USER  = 'DB_USER';
    const ENV_DB_NAME  = 'DB_NAME';
    const ENV_DST_DIR = 'DST';

    const ENV_DIRECTORIES = 'DIRECTORIES';

    /**
     * @var callable
     */
    private $databaseBuilder;

    /**
     * @var callable
     */
    private $directoriesBuilder;

    /**
     * @var callable
     */
    private $tmpBuilder;

    /**
     * @var callable
     */
    private $outputBuilder;

    public function __construct()
    {
        $this->databaseBuilder = function () {
            throw new \RuntimeException("No method of fetching database config chosen.");
        };
        $this->directoriesBuilder = function () {
            throw new \RuntimeException("No method of fetching directories chosen.");
        };
        $this->tmpBuilder = function () {
            return new Directory(sys_get_temp_dir());
        };
        $this->outputBuilder = function () {
            throw new \RuntimeException("No method of fetching destination directory chosen.");
        };
    }

    public function build() : Config
    {
        return new Config(
            ($this->databaseBuilder)(),
            ($this->tmpBuilder)(),
            ($this->outputBuilder)(),
            ...($this->directoriesBuilder)()
        );
    }

    public function directoriesFromEnv()
    {
        $this->directoriesBuilder = function () {
            return array_map(function ($dir) {
                $args = explode(':', $dir);
                return new Directory(...$args);
            }, explode(';', $_ENV[self::ENV_DIRECTORIES]));
        };
    }

    public function dbFromEnv()
    {
        $this->databaseBuilder = function () {
            return new Database(
                $_ENV[self::ENV_DB_NAME],
                $_ENV[self::ENV_DB_USER],
                $_ENV[self::ENV_DB_PASS],
                $_ENV[self::ENV_DB_HOST],
                (int) $_ENV[self::ENV_DB_PORT]
            );
        };
    }

    public function dstFromEnv()
    {
        $this->outputBuilder = function () {
            $dst = $_ENV[self::ENV_DST_DIR];

            return new Output(new Directory(dirname($dst)), basename($dst));
        };
    }

    /**
     * @deprecated
     */
    public function setDirectories(string... $directories)
    {
        $this->directoriesBuilder = function () use ($directories) {
            return array_map(function ($directory) {
                return new Directory($directory);
            }, $directories);
        };
    }
}
