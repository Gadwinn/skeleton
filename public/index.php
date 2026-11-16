<?php

declare(strict_types=1);

use G\Framework\ApplicationFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

// .env files into $_ENV: composer require gadwinn/dotenv, then uncomment
// (new G\Dotenv\Dotenv())->load(dirname(__DIR__) . '/.env')->store();

ApplicationFactory::fromDirectory(dirname(__DIR__))->run();
