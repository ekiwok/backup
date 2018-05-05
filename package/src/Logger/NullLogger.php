<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup\Logger;

use Ekiwok\WPBackup\SimpleLogger;

class NullLogger implements SimpleLogger
{
    public function log(string $message): void
    {
    }
}
