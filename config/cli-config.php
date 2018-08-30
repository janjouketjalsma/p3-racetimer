<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require 'vendor/autoload.php';

$settings      = require __DIR__ .'/../P3RaceTimer/settings.php';

$doctrineService  = new P3RaceTimer\Service\Doctrine($settings['settings']['doctrine']);
$entityManager    = $doctrineService->entityManager();

return ConsoleRunner::createHelperSet($entityManager);
