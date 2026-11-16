<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

// Only what the autowiring cannot guess: any instantiable class is built on its own.
return [
    // An interface: which implementation to use
    // Psr\Log\LoggerInterface::class => G\Log\Logger::class,

    // An object that needs configuration values
    // PDO::class => static fn (): PDO => new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']),

    // A service built from other services
    // App\Mailer::class => static fn (ContainerInterface $c): App\Mailer
    //     => new App\Mailer($c->get(Psr\Log\LoggerInterface::class), $_ENV['MAIL_FROM']),
];
