<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup\Store;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

final class ArchiveOperationException extends \RuntimeException
{
    public static function from(string $errorMessage) : ArchiveOperationException
    {
        return new self($errorMessage);
    }

    public static function fromIOException(IOExceptionInterface $e) : ArchiveOperationException
    {
        return new self(sprintf("%s: %s", get_class($e) , $e->getPath()), 0, $e);
    }
}
