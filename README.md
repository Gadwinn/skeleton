# gadwinn/skeleton

Starting project for applications built on [`gadwinn/framework`](https://github.com/Gadwinn/framework):
controllers without parent class, dependency injection, configuration in PHP, `public/` as the
only web root.

## Installation

```bash
composer create-project gadwinn/skeleton my-app
cd my-app
composer serve          # http://localhost:8000
```

Requires PHP 8.3+.

## Layout

```
my-app/
├── public/
│   ├── index.php          # the only entry point, the only web root
│   └── .htaccess          # Apache only: routes every request to index.php
├── config/
│   ├── services.php       # what the autowiring cannot guess
│   ├── middlewares.php    # middleware stack, in order of execution
│   └── routes.php         # base path, loading of the controller routes
├── src/Controller/        # your controllers (namespace App\)
├── tests/
├── .env.example
└── composer.json
```

## Running locally

```bash
composer serve                         # same as: php -S localhost:8000 -t public
```

Served from a sub-folder of a local web server (e.g. `http://localhost/my-app/public/`),
declare the base path in `config/routes.php`:

```php
$router->setBasePath('/my-app/public');
```

## Adding a route

Routes are declared on the controllers: every class of `src/Controller` is read, nothing
to register.

```php
// src/Controller/UserController.php
namespace App\Controller;

use G\Router\Attribute\{Group, Route};

#[Group('/users', name: 'users.')]          // optional: common path and name prefix
final class UserController
{
    public function __construct(private readonly UserRepository $users)
    {
    }

    #[Route('/{id:i}', name: 'show')]       // GET /users/{id}, named users.show
    public function show(int $id): array
    {
        return $this->users->find($id);   // an array is sent as JSON, a string as HTML
    }
}
```

`#[Route]` takes `methods: 'POST'` or `methods: ['PUT', 'PATCH']` (default `GET`) and can be
repeated. Routes can still be mapped by hand in `config/routes.php`.

A controller needs no declaration: the container builds it and injects its constructor
dependencies. Route parameters are injected by name, the request by type
(`ServerRequestInterface $request`). See the README of `gadwinn/framework` for every rule and
for custom response converters.

## Adding a service

Only what the autowiring cannot guess goes in `config/services.php`: an interface and its
implementation, or a class that needs configuration values.

```php
return [
    Psr\Log\LoggerInterface::class => G\Log\Logger::class,
    PDO::class => static fn (): PDO => new PDO($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']),
];
```

## Optional packages

Every optional package (the `suggest` list of `composer.json`) has a commented block, ready to
enable, in `config/services.php`, and the middlewares are listed in their order of execution
in `config/middlewares.php`. To enable one: run the `composer require` command written above
its block, then uncomment the block and, for a middleware, its line.

| Middlewares (in stack order) | Services |
|---|---|
| `trusted-proxy-middleware`, `error-catcher-middleware`, `fail-sentry-middleware`, `http-method-middleware`, `csrf` | `dotenv`, `psr-log`, `psr-simple-cache`, `psr-event-dispatcher`, `psr-clock`, `psr-http-client`, `orm`, `translator`, `validator`, `jwt`, `debug` |

All of them are under `gadwinn/`. A middleware without settings only needs its line in
`config/middlewares.php`, for instance:

```bash
composer require gadwinn/http-method-middleware
```

```php
return [
    G\HttpMethodMiddleware\MethodOverrideMiddleware::class,
    G\HttpMethodMiddleware\BodyParserMiddleware::class,
    G\Router\Middleware\RouterMiddleware::class,
];
```

## Debug bar

**Development only.** `composer require gadwinn/debug`, then uncomment:

- in `config/middlewares.php`: the `$dev` line, `DebugBarMiddleware` (first) and
  `CaptureRequestMiddleware` (after the router); both are only added when `APP_ENV=dev`;
- in `config/services.php`: the `DebugBar` block, and the replacements given there for the
  logger and the orm to get the Logs and SQL panels.

HTML pages then show the bar at the bottom; every response, JSON included, gets a
`Server-Timing` header visible in the browser's developer tools. Inject
`G\Debug\Collector\DumpCollector` where you want to inspect a value: `$dumps->dump($value)`.

## Environment variables

Copy `.env.example` to `.env`, run `composer require gadwinn/dotenv` and uncomment the
line that loads it in `public/index.php`: the values are then available in `$_ENV`.

## Web servers

The web root is always `public/`; every request that is not an existing file goes to
`public/index.php`.

### nginx (with PHP-FPM)

```nginx
server {
    listen 80;
    server_name example.com;
    root /var/www/my-app/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location = /index.php {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php-fpm.sock;
    }
}
```

### Apache

```apache
<VirtualHost *:80>
    ServerName example.com
    DocumentRoot /var/www/my-app/public

    <Directory /var/www/my-app/public>
        AllowOverride None
        Require all granted
        FallbackResource /index.php
    </Directory>
</VirtualHost>
```

Without access to the virtual host, the `public/.htaccess` file does the same with `mod_rewrite`.

### FrankenPHP

```caddyfile
example.com {
    root * /var/www/my-app/public
    php_server
}
```

In worker mode, the application is built once and serves every request; replace the content
of `public/index.php` with:

```php
<?php

declare(strict_types=1);

use G\Framework\ApplicationFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = ApplicationFactory::fromDirectory(dirname(__DIR__));

if (!function_exists('frankenphp_handle_request')) {
    $app->run();
    return;
}

$handler = static fn () => $app->run();
while (frankenphp_handle_request($handler)) {
    gc_collect_cycles();
}
```

```caddyfile
{
    frankenphp {
        worker /var/www/my-app/public/index.php
    }
}
```

Behind a reverse proxy (Traefik, nginx), enable `gadwinn/trusted-proxy-middleware` to get the
real client IP, scheme and host.

## Tests

```bash
composer test-all       # security audit, code style, static analysis, unit tests, magic numbers
```

## License

MIT
