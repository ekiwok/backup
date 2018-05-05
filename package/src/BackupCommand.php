<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

use Ekiwok\WPBackup\Logger\StaticLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommand extends Command
{
    public function configure()
    {
        parent::configure();
        $this->setName('wp-backup');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        StaticLogger::setLogger($output);

        $configBuilder = new ConfigBuilder();
        $configBuilder->dbFromEnv();
        $configBuilder->directoriesFromEnv();
        $configBuilder->dstFromEnv();
        $config = $configBuilder->build();

        $archive = Backup::from($config);
        $archive->compress();
        $archive->mv((string) $config->getOutput());

        $output->writeln(sprintf("Created archive: %s", $archive->getRealPath()));
    }
}
