<?php

declare(strict_types=1);

// Order = order of execution. Everything is optional but the router.
// Optional packages: run the composer command given in config/services.php, uncomment the
// line here and, when it has one, the block of config/services.php.

// $dev = ($_ENV['APP_ENV'] ?? 'prod') === 'dev';   // gadwinn/debug: development only

return [
    // ...($dev ? [G\Debug\DebugBarMiddleware::class] : []),   // first: even error pages get the bar
    // G\TrustedProxyMiddleware\TrustedProxyMiddleware::class,  // real client IP before anyone uses it
    // G\ErrorCatcherMiddleware\ErrorCatcherMiddleware::class,  // catches the errors of what follows
    // G\FailSentryMiddleware\FailSentryMiddleware::class,      // rejects blocked IPs before any work
    // G\HttpMethodMiddleware\MethodOverrideMiddleware::class,  // before csrf: checks the real method
    // G\HttpMethodMiddleware\BodyParserMiddleware::class,      // before csrf: token read in the body
    // G\Csrf\Middleware\CsrfMiddleware::class,
    G\Router\Middleware\RouterMiddleware::class,
    // ...($dev ? [G\Debug\CaptureRequestMiddleware::class] : []),   // after the router: sees the route
];
