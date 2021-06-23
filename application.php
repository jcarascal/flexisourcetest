<?php
// application.php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$command = new CreateCustomersCommand();
$application = new Application();
$application->add($command);
$application->setDefaultCommand($command->getName());
$application->run();