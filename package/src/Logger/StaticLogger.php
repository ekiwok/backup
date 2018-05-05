<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup\Logger;

use Ekiwok\WPBackup\SimpleLogger;
use Symfony\Component\Console\Output\OutputInterface;

final class StaticLogger implements SimpleLogger
{
    /**
     * @var SimpleLogger
     */
    static private $logger;

    /**
     * @var OutputInterface
     */
    private $output;

    private function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    private function __clone()
    {
    }

    static public function setLogger(OutputInterface $output)
    {
        if (self::$logger) {
            throw new \RuntimeException(sprintf('Logger %s already initialised!', get_class(self::$logger)));
        }
        self::$logger = new self($output);
    }

    static public function get() : SimpleLogger
    {
        if (!self::$logger) {
            self::$logger = new NullLogger();
        }

        return self::$logger;
    }

    public function log(string $message) : void
    {
        $this->output->writeln(sprintf("<info>%s</info>", $message));
    }
}
