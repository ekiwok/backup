<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

interface SimpleLogger
{
    public function log(string $message) : void;
}
