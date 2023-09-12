<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Predis\Client;

class FetchCurrencyRatesCommand extends Command
{
    protected static $defaultName = 'fetch:currency-rates';

    private $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
        ]);
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: Имплементировать логику сбора данных с cbr.ru за 180 дней.

        return Command::SUCCESS;
    }
}