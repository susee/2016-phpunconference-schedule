<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use PhpUnconf\Command\BuildCommand;

$application = new Application();
$application->add(new BuildCommand());
$application->run();
