<?php
/**
 * @var \DI\Container $container
 */

use Predis\Client;
use DI\Bridge\Slim\Bridge;
use DI\Container;

require __DIR__ . '/config.php';

require __DIR__ . '/vendor/autoload.php';

$container = new Container;

/** @var array $config */
$redis = new Client($config['redis']);

$container->set('redis', $redis);

$app = Bridge::create($container);

