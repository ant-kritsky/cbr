<?php

namespace App;

use DI\Container;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Queue
{
    private $connection;
    private $channel;

    public function __construct(Container $container)
    {
        $this->connection = $container->get(AMQPStreamConnection::class);
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare(RABBIT_QUEUE, false, false, false, false);
    }

    public function add($message): void
    {
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', RABBIT_QUEUE);
    }

    public function execute($callback): void
    {

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $this->channel->basic_consume(RABBIT_QUEUE, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}