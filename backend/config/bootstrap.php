<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    (new Dotenv(false))->loadEnv(__DIR__ . '/../.env');
}

$_SERVER += $_ENV;
$_SERVER['APP_ENV'] = $_ENV['APP_ENV'] = ($_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? null) ?: 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? $_SERVER['APP_ENV'] !== 'prod';
$_SERVER['APP_DEBUG'] = $_ENV['APP_DEBUG'] = (int) $_SERVER['APP_DEBUG'] || filter_var($_SERVER['APP_DEBUG'], FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
