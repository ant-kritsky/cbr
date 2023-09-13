#!/usr/bin/env php
<?php

/** @var \DI\Container $container */
require __DIR__ . '/bootstrap.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add($container->get(App\Command\FetchCurrencyRatesCommand::class));

$application->run();