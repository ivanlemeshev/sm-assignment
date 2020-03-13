<?php

require_once dirname(__DIR__) .'/vendor/autoload.php';

use App\Handler\Statistics;
use App\Provider\SupermetricsPosts;
use App\Service\Supermetrics\Client;
use App\Service\Supermetrics\Credentials;
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

session_start();

header('Content-Type: application/json');

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

$logger = new Logger('supermetrics-app');
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/supermetrics-app.log', Logger::DEBUG));

if (!is_string($_ENV['SUPERMETRICS_BASE_URL'])) {
    $logger->error('SUPERMETRICS_BASE_URL should be a non-empty string');
    return;
}

if (!is_string($_ENV['SUPERMETRICS_CLIENT_ID'])) {
    $logger->error('SUPERMETRICS_CLIENT_ID should be a non-empty string');
    return;
}

if (!is_string($_ENV['SUPERMETRICS_EMAIL'])) {
    $logger->error('SUPERMETRICS_EMAIL should be a non-empty string');
    return;
}

if (!is_string($_ENV['SUPERMETRICS_NAME'])) {
    $logger->error('SUPERMETRICS_NAME should be a non-empty string');
    return;
}


$supermetrics = new Client($_ENV['SUPERMETRICS_BASE_URL'], $logger);

$credentials = new Credentials(
    $_ENV['SUPERMETRICS_CLIENT_ID'],
    $_ENV['SUPERMETRICS_EMAIL'],
    $_ENV['SUPERMETRICS_NAME']
);

$posts = new SupermetricsPosts($supermetrics, $credentials);

$statistics = new Statistics(...$posts->getPosts());

echo json_encode($statistics->show());
