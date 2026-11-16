<?php

declare(strict_types=1);

// Order = order of execution. Everything is optional but the router.
return [
    // G\TrustedProxyMiddleware\TrustedProxyMiddleware::class,   // behind a reverse proxy, to declare in services.php
    // G\ErrorCatcherMiddleware\ErrorCatcherMiddleware::class,   // to declare in services.php, with its error handler
    // G\HttpMethodMiddleware\MethodOverrideMiddleware::class,
    // G\HttpMethodMiddleware\BodyParserMiddleware::class,
    G\Router\Middleware\RouterMiddleware::class,
];
