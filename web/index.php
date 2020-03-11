<?php

require_once dirname(__DIR__) .'/vendor/autoload.php';

use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use App\Service\Supermetrics;

$env = Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

$logger = new Logger('supermetrics-app');
$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/supermetrics-app.log', Logger::DEBUG));
$logger->info('test');


$supermetrics = new Supermetrics($_ENV['SUPERMETRICS_BASE_URL'], $logger);
$token = $supermetrics->register($_ENV['SUPERMETRICS_CLIENT_ID'], $_ENV['SUPERMETRICS_EMAIL'], $_ENV['SUPERMETRICS_NAME']);

$result = [];

if ($token !== '') {
    for ($page = 1; $page < 11; $page++) {
        $posts = $supermetrics->getPosts($token, $page);
        $result = array_merge($result, $posts);
    }
}

// TODO Average character length of a post / month
// TODO Longest post by character length / month
// TODO Total posts split by week
// TODO Average number of posts per user / month

header('Content-Type: application/json');
echo json_encode($result);
