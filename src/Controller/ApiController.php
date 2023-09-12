<?php

namespace App\Controller;

use App\CurrencyRate;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Container;

class ApiController
{
    private $container;
    /** @var Redis */
    private $redis;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->redis = $container->get('redis');
    }

    function rates(Request $request, Response $response)
    {
        $params = $request->getQueryParams();
        $date = $params['date'] ?? null;
        $currency = $params['currency'] ?? null;
        $base = $params['base'] ?? CurrencyRate::BASE_CURRENCY;

        if (is_null($date) || is_null($currency)) {
            $response = $response->withStatus(400);
            $response->getBody()->write('Parameters "date" and "currency" is required!');

            return $response->withHeader('Content-Type', 'application/json');
        }

        $rate = $this->redis->get($currency . ':' . $base);

        if (!$rate) {
            $response = $response->withStatus(200);
        }

        $response->getBody()->write(json_encode([
            'date' => $date,
            'currency' => $currency,
            'base' => $base,
            'rate' => $rate,
            'difference' => $rate
        ]));


        return $response->withHeader('Content-Type', 'application/json');
    }
}