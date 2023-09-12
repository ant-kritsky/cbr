<?php

use DI\Container;
use Predis\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;

require __DIR__ . '/config.php';

require __DIR__ . '/vendor/autoload.php';

$container = new Container;

/** @var array $config */
$redis = new Client($config['redis']);

$container->set('redis', $redis);

$connection = new AMQPStreamConnection(
    $config['AMQP']['host'],
    $config['AMQP']['port'],
    $config['AMQP']['user'],
    $config['AMQP']['password']
);
$container->set('rabbit_connection', $connection);

$channel = $connection->channel();
$channel->queue_declare('date_queue', false, false, false, false);
$container->set('rabbit_channel', $channel);
