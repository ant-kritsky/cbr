<?php

const RABBIT_CONNECTION = 'rabbit_connection';
const RABBIT_CHANEL = 'rabbit_channel';
const RABBIT_QUEUE = 'date_queue';

require __DIR__ . '/vendor/autoload.php';

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions('di.php');

$container = $builder->build();

