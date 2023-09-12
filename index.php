<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set("display_errors", '1');

use App\Controller\ApiController;
use DI\Bridge\Slim\Bridge;

require __DIR__ . '/bootstrap.php';

/** @var \DI\Container $container */
$app = Bridge::create($container);

$app->any('/', [ApiController::class, 'rates']);

$app->run();