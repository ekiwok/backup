<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup\Store;

use Ekiwok\WPBackup\Directory;
use Ekiwok\WPBackup\Logger\NullLogger;
use Ekiwok\WPBackup\SimpleLogger;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

final class ShellTarGzipArchive extends Archive
{
    const TAR_COMMAND = 'tar -czf %s %s > /dev/null';

    const ARCHIVE_TMP_DIR = 'archive';
    const ARCHIVE_TMP_NAME = 'archive.tar.gz';

    /**
     * @var string[]
     */
    private $files = [];

    /**
     * @var Directory[]
     */
    private $directories;

    /**
     * @var string
     */
    private $realPath;

    /**
     * @var Directory
     */
    private $tmpDir;

    /**
     * @var NullLogger
     */
    private $logger;

    public function __construct(Directory $tmpDir, SimpleLogger $logger)
    {
        parent::__construct();
        $this->realPath = sprintf('%s/%s', $tmpDir, self::ARCHIVE_TMP_NAME);
        $this->tmpDir = $tmpDir;
        $this->logger = $logger;
    }

    public function getRealPath(): string
    {
        return $this->realPath;
    }

    public function addDirectory(Directory $directory): void
    {
        $this->logger->log(sprintf("<info>Add directory: %s</info>", $directory));
        $this->directories[] = $directory;
    }

    public function addFile(string $fileRealPath): void
    {
        $this->logger->log(sprintf("<info>Add file: %s</info>", $fileRealPath));
        $this->files[] = $fileRealPath;
    }

    /**
     * @throws ArchiveOperationException
     */
    public function compress() : void
    {
        $tmpArchiveRealPath = sprintf('%s/%s', $this->tmpDir, self::ARCHIVE_TMP_DIR);

        try {
            $this->fs->mkdir($tmpArchiveRealPath);

            foreach ($this->files as $file) {
                $fileDest = sprintf("%s/%s", $tmpArchiveRealPath, basename($file));
                $this->logger->log(sprintf('<info>Copy %s to %s</info>', $file, $fileDest));
                $this->fs->copy($file, $fileDest);
            }

            foreach ($this->directories as $directory) {
                $directoryArchivePath = sprintf(
                    "%s/%s",
                    $tmpArchiveRealPath,
                    $directory->getOptionalNameOrBasePath()
                );
                $this->logger->log(sprintf('<info>Mirror %s to %s</info>', $directory, $directoryArchivePath));
                $this->fs->mirror($directory, $directoryArchivePath);
            }

            exec(
                sprintf(self::TAR_COMMAND,
                    $this->realPath,
                    $tmpArchiveRealPath),
                $output,
                $result
            );

            if ($result !== 0) {
                throw ArchiveOperationException::from($output);
            }

        } catch (IOException $e) {
            throw ArchiveOperationException::fromIOException($e);
        } finally {
            $this->fs->remove($tmpArchiveRealPath);
        }
    }
}
