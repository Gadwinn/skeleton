<?php

declare(strict_types=1);

use G\Framework\ApplicationFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

// .env files into $_ENV: composer require gadwinn/dotenv, then uncomment
// (new G\Dotenv\Dotenv())->load(dirname(__DIR__) . '/.env')->store();

$app = ApplicationFactory::fromDirectory(dirname(__DIR__));

// Classic mode (PHP-FPM, Apache, built-in server, FrankenPHP without worker): one request.
if (!function_exists('frankenphp_handle_request')) {
    $app->run();
    return;
}

// FrankenPHP worker mode: the application is built once and serves every request;
// run() resets the services of config/reset.php after each of them.
$handler = static fn () => $app->run();
while (frankenphp_handle_request($handler)) {
    gc_collect_cycles();
}
