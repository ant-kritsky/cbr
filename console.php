#!/usr/bin/env php
<?php

require __DIR__ . '/bootstrap.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... добавьте команды

$application->run();