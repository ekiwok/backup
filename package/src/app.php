<?php

require_once __DIR__ . '/../vendor/autoload.php';

define('VERSION', '0.1.0');

$app = new \Symfony\Component\Console\Application();
$app->add(new \Ekiwok\WPBackup\BackupCommand());
$app->run();