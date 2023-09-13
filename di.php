<?php
declare(strict_types=1);

use function DI\autowire;
use function DI\get;
use \Predis\Client as Redis;
use \PhpAmqpLib\Connection\AMQPStreamConnection;

require __DIR__ . '/config.php';

return [
    AMQPStreamConnection::class => autowire()
        ->constructorParameter('host', $config['AMQP']['host'])
        ->constructorParameter('port', $config['AMQP']['port'])
        ->constructorParameter('user', $config['AMQP']['user'])
        ->constructorParameter('password', $config['AMQP']['password']),

    Redis::class => autowire()->constructorParameter('parameters', $config['redis'])

];