<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

$opts = getopt('e:', ['environment:']);
$environment = $opts['e'] ?? $opts['environment'] ?? '';

$envFile = $environment === 'test' ? '/.env.test' : '/.env';

if ($environment != 'prod') {
    (new Dotenv(true))->load(__DIR__ . $envFile);
}

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds',
    ],
    'environments' => [
        'default_database' => $environment,
        'default_migration_table' => 'migration',
        $environment => [
            'adapter' => 'pgsql',
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'name' => getenv('DB_NAME'),
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
    ],
];
