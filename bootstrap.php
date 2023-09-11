<?php
/**
 * @var \DI\Container $container
 */

use Predis\Client;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

/** @var array $config */
$redis = new Client($config['redis']);

$container->set(Client::class, $redis);

