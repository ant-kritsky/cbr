<?php

namespace App\Command;

use App\CbrCurrencyRateFetcher;
use App\Queue;
use DI\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Predis\Client as Redis;
use DateTime;

class FetchCurrencyRatesCommand extends Command
{
    const LIMIT_DAYS = 180;
    protected static $defaultName = 'fetch:currency-rates';

    private $container;

    /** @var \Predis\Client */
    private $redis;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->redis = $container->get(Redis::class);

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queue = $this->container->get(Queue::class);

        $callback = function ($msg) use ($queue) {
            $date = $msg->body;
            $rates = CbrCurrencyRateFetcher::getRates($date);
            $this->redis->set($date, serialize($rates));

            echo "Currency rates received on $date\n";

            $yesterday = date("Y-m-d", strtotime($date . " -1 day"));

            if ($this->isOlderThenLimit($date) && !$this->redis->get($yesterday)) {
                $queue->add($yesterday);
            }
        };

        $queue->execute($callback);
        $queue->close();

        return Command::SUCCESS;
    }

    public function isOlderThenLimit($date)
    {
        $dateDiffDays = date_diff(new DateTime(), new DateTime($date))->days;

        return $dateDiffDays < self::LIMIT_DAYS;
    }
}