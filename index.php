<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set("display_errors", 1);

use App\Controller\ApiController;

require __DIR__ . '/bootstrap.php';

/** @var \Slim\App $app */
$app->any('/', [ApiController::class, 'rates']);

$app->run();