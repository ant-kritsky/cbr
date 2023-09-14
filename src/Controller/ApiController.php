<?php

namespace App\Controller;

use App\CurrencyRate;
use App\Queue;
use Predis\Client as Redis;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;

class ApiController
{
    private $container;
    /** @var \Predis\Client */
    private $redis;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->redis = $container->get(Redis::class);
    }

    function rates(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $date = $params['date'] ?? date('Y-m-d');
        $currency = $params['currency'] ? strtoupper($params['currency']) : null;
        $base = $params['base'] ? strtoupper($params['base']) : CurrencyRate::BASE_CURRENCY;

        if (is_null($date) || is_null($currency)) {
            $response = $response->withStatus(400);
            $response->getBody()->write('Parameters "date" and "currency" is required!');

            return $response->withHeader('Content-Type', 'application/json');
        }

        $rateKey = $date . ':' . $currency . ':' . $base;
        $rate = $this->redis->get($rateKey);

        if (!$rate) {
            // Проверяем наличие курсов на дату в Redis
            $dateRates = $this->redis->get($date);
            $yesterday = date("Y-m-d", strtotime($date . " -1 day"));
            $yesterdayRates = $this->redis->get($yesterday);

            if (!$dateRates || !$yesterdayRates) {
                $queue = $this->container->get(Queue::class);
                $queue->add($dateRates ? $yesterday : $date);
                $queue->close();

                return $this->errorResponse($response, "Данных на $date еще нет! Пожалуйста, обратитесь позже.");
            }

            $currencyRate = number_format((new CurrencyRate($dateRates))->getRate($currency, $base), 4);
            $yesterdayRate = number_format((new CurrencyRate($yesterdayRates))->getRate($currency, $base), 4);
            $difference = number_format($currencyRate - $yesterdayRate, 4);
            $rate = [
                'value' => $currencyRate,
                'difference' => ($difference < 0 ? '' : '+') . $difference
            ];
            $this->redis->set($rateKey, serialize($rate));
        } else {
            $rate = unserialize($rate);
        }

        $response->getBody()->write(json_encode($rate));

        return $response->withHeader('Content-Type', 'application/json');
    }

    private function errorResponse(Response $response, $message): Response
    {
        $response->getBody()->write(json_encode([
            'error' => true,
            'message' => $message,
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}