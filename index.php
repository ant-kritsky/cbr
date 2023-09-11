<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Bridge\Slim\Bridge;
use DI\Container;
use App\Controller\ApiController;

$container = new Container;
$container->set();
require __DIR__ . '/bootstrap.php';

$app = Bridge::create($container);


$app->get('/', ApiController::class, ':test');

$app->get('/{currency}/[{base}]', function (Request $request, Response $response, $args) use ($redis) {
    $currency = $args['currency'];
    $base = $args['base'] ?? 'RUR';

    $rate = $redis->get($currency . ':' . $base);

    if (!$rate) {

    }

    $response->getBody()->write(json_encode([
        'currency' => $currency,
        'base' => $base,
        'rate' => $rate
    ]));

    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();