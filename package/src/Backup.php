<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

use Ekiwok\WPBackup\Logger\StaticLogger;
use Ekiwok\WPBackup\Store\Archive;
use Ekiwok\WPBackup\Store\ShellTarGzipArchive;
use Symfony\Component\Finder\Finder;

final class Backup
{
    const MYSQL_DUMP = 'mysqldump -u %s -p%s --host=%s --port=%d %s > %s';

    const DUMP_NAME = 'database.sql';

    static public function from(Config $config) : Archive
    {
        $archive = new ShellTarGzipArchive($config->getTmp(), StaticLogger::get());

        // dump mysql
        $mysqldump = self::mysqldump($config->getDatabase(), $config->getTmp());

        // add mysql to archive
        $archive->addFile($mysqldump);

        // ad directories to archive
        foreach ($config->getDirectories() as $directory) {
            $archive->addDirectory($directory);
        }

        return $archive;
    }

    static private function mysqldump(Database $database, Directory $tmpDir) : string
    {
        $tmpDumpFile = sprintf("%s/%s", $tmpDir, self::DUMP_NAME);
        $backupCommand = sprintf(self::MYSQL_DUMP,
            $database->getUser(),
            $database->getPassword(),
            $database->getHost(),
            (int) $database->getDbPort(),
            $database->getName(),
            $tmpDumpFile);

        exec($backupCommand, $output, $result);

        if ($result !== 0) {
            throw new \RuntimeException($output ? implode($output, ',') : "mysqldump failed");
        }

        return $tmpDumpFile;
    }

    /**
     * @deprecated
     */
    static function parseDirectory(Directory $directory) : array
    {
        $parsed = [];

        $finder = new Finder();
        $finder->files()->in((string) $directory);

        foreach ($finder as $file) {
            $filename = $file->getFilename();
            $localname = str_replace($directory, '', $file->getRealPath());

            $parsed[] = [$filename, $localname];
        }

        return $parsed;
    }

    /**
     * @deprecated
     */
    static private function flatten(array $array) : iterable
    {
        foreach ($array as $nested) {
            foreach ($nested as $elem) {
                yield $elem;
            }
        }
    }
}
