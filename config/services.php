<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

// Only what the autowiring cannot guess: any instantiable class is built on its own.
// A service factory receives the container and any other service it type-hints.
//
// Each optional package below has a ready-made block, in the order of the "suggest" list of
// composer.json: run the composer command, then uncomment the block.
return [
    // Your own services, for instance:
    // App\Mailer::class => static fn (ContainerInterface $c): App\Mailer
    //     => new App\Mailer($c->get(Psr\Log\LoggerInterface::class), $_ENV['MAIL_FROM']),

    // --- composer require gadwinn/trusted-proxy-middleware -----------------------------------
    // Behind a reverse proxy: comma-separated IP addresses or CIDR ranges of the proxies
    // G\TrustedProxyMiddleware\TrustedProxyMiddleware::class => static fn ()
    //     => new G\TrustedProxyMiddleware\TrustedProxyMiddleware(
    //         explode(',', $_ENV['TRUSTED_PROXIES'] ?? '127.0.0.1'),
    //     ),

    // --- composer require gadwinn/error-catcher-middleware ------------------------------------
    // Uncaught exceptions and PHP errors become a 500 response (log the error here)
    // G\ErrorCatcherMiddleware\ErrorCatcherMiddleware::class
    //     => static fn (Psr\Http\Message\ResponseFactoryInterface $responses)
    //     => new G\ErrorCatcherMiddleware\ErrorCatcherMiddleware(
    //         static function (Throwable $error) use ($responses): Psr\Http\Message\ResponseInterface {
    //             $response = $responses->createResponse(500);
    //             $response->getBody()->write(
    //                 ($_ENV['APP_ENV'] ?? 'prod') === 'dev' ? (string) $error : 'Internal Server Error'
    //             );
    //
    //             return $response->withHeader('Content-Type', 'text/plain; charset=utf-8');
    //         },
    //     ),

    // --- composer require gadwinn/fail-sentry-middleware --------------------------------------
    // Blocks for an hour the IP addresses with 5 failed requests (400, 401, 403, 405, 422, 429)
    // G\FailSentryMiddleware\Storage\StorageInterface::class => static fn ()
    //     => new G\FailSentryMiddleware\Storage\FileStorage(dirname(__DIR__) . '/var/fail-sentry.json'),
    // G\FailSentryMiddleware\FailSentryMiddleware::class => static fn (
    //     G\FailSentryMiddleware\Storage\StorageInterface $storage,
    //     Psr\Http\Message\ResponseFactoryInterface $responses,
    // ) => new G\FailSentryMiddleware\FailSentryMiddleware(
    //     $storage,
    //     $responses,
    //     ipAttribute: 'client_ip',   // IP found by trusted-proxy-middleware, when it is enabled
    // ),

    // --- composer require gadwinn/http-method-middleware --------------------------------------
    // The values below are the defaults: both middlewares also work without any declaration
    // G\HttpMethodMiddleware\MethodOverrideMiddleware::class => static fn ()
    //     => new G\HttpMethodMiddleware\MethodOverrideMiddleware(['PUT', 'PATCH', 'DELETE'], '_method'),

    // --- composer require gadwinn/csrf --------------------------------------------------------
    // Tokens kept in the PHP session; the form field is "_token"
    // G\Csrf\TokenGenerator\TokenGeneratorInterface::class => G\Csrf\TokenGenerator\RandomTokenGenerator::class,
    // G\Csrf\TokenStorage\TokenStorageInterface::class => G\Csrf\TokenStorage\SessionTokenStorage::class,
    // G\Csrf\CsrfManagerInterface::class => G\Csrf\CsrfManager::class,
    // G\Csrf\Middleware\CsrfMiddleware::class => static fn (
    //     G\Csrf\CsrfManagerInterface $csrf,
    //     Psr\Http\Message\ResponseFactoryInterface $responses,
    // ) => new G\Csrf\Middleware\CsrfMiddleware(
    //     $csrf,
    //     new G\Http\Server\FallbackResponseRequestHandler($responses, 403),   // invalid token: 403
    // ),

    // --- composer require gadwinn/dotenv ------------------------------------------------------
    // Nothing to declare: uncomment the line that loads the .env file in public/index.php

    // --- composer require gadwinn/psr-log -----------------------------------------------------
    // Psr\Log\LoggerInterface::class => static fn () => new G\Log\Logger(
    //     $_ENV['LOG_LEVEL'] ?? Psr\Log\LogLevel::INFO,
    //     [new G\Log\Output\LogOutputFile(dirname(__DIR__) . '/var/log/app.log')],
    // ),

    // --- composer require gadwinn/psr-simple-cache --------------------------------------------
    // Psr\SimpleCache\CacheInterface::class => static fn ()
    //     => new G\SimpleCache\Cache(new G\SimpleCache\Storage\FileStorage(dirname(__DIR__) . '/var/cache')),

    // --- composer require gadwinn/psr-event-dispatcher ----------------------------------------
    // Add the listeners with $c->get(G\EventDispatcher\ListenerProvider::class)->addListener(...)
    // Psr\EventDispatcher\ListenerProviderInterface::class => G\EventDispatcher\ListenerProvider::class,
    // Psr\EventDispatcher\EventDispatcherInterface::class => G\EventDispatcher\EventDispatcher::class,

    // --- composer require gadwinn/psr-clock ---------------------------------------------------
    // Psr\Clock\ClockInterface::class => G\Clock\SystemClock::class,

    // --- composer require gadwinn/psr-http-client ---------------------------------------------
    // Psr\Http\Client\ClientInterface::class => G\Http\Client\Client::class,

    // --- composer require gadwinn/orm ---------------------------------------------------------
    // DATABASE_URL: "mysql://user:password@127.0.0.1:3306/app", "sqlite:///path/to/app.db"...
    // G\Orm\Contract\DbManagerInterface::class
    //     => static fn () => G\Orm\Factory\OrmFactory::connect($_ENV['DATABASE_URL']),

    // --- composer require gadwinn/translator --------------------------------------------------
    // G\Translator\Runtime\Translator::class => static function (): G\Translator\Runtime\Translator {
    //     $source = new G\Translator\Io\Source\FileSource(dirname(__DIR__) . '/translations/fr_FR.po');
    //     $registry = new G\Translator\Runtime\BagRegistry();
    //     $registry->register(
    //         'fr-FR',
    //         (new G\Translator\Io\Detector\FormatDetector())->parserFor($source)->parse($source, locale: 'fr-FR'),
    //     );
    //     $translator = new G\Translator\Runtime\Translator($registry);
    //     $translator->setLocale('fr-FR');
    //
    //     return $translator;
    // },

    // --- composer require gadwinn/validator ---------------------------------------------------
    // G\Validator\ValidatorInterface::class => G\Validator\Validator::class,

    // --- composer require gadwinn/jwt --------------------------------------------------------
    // Key used with G\Jwt\Jwt::encode() and Jwt::verify(); JWT_SECRET: at least 32 random bytes
    // G\Jwt\ValueObject\Key::class => static fn ()
    //     => new G\Jwt\ValueObject\Key($_ENV['JWT_SECRET'], algorithm: G\Jwt\Enum\Algorithm::HS256),

    // --- composer require gadwinn/debug -------------------------------------------------------
    // Development only (see config/middlewares.php). The logger of the application goes through
    // the LogCollector, and the orm sends its queries to the QueryCollector.
    // G\Debug\DebugBar::class => static fn (ContainerInterface $c) => new G\Debug\DebugBar(
    //     new G\Debug\Collector\TimeMemoryCollector(),
    //     new G\Debug\Collector\RequestCollector(),
    //     new G\Debug\Collector\RouteCollector(),
    //     $c->get(G\Debug\Collector\LogCollector::class),
    //     $c->get(G\Debug\Collector\QueryCollector::class),
    //     $c->get(G\Debug\Collector\DumpCollector::class),   // inject it where you dump values
    // ),
    // With gadwinn/psr-log: replace its block above by these two entries
    // G\Debug\Collector\LogCollector::class => static fn () => new G\Debug\Collector\LogCollector(
    //     new G\Log\Logger($_ENV['LOG_LEVEL'] ?? Psr\Log\LogLevel::INFO),
    // ),
    // Psr\Log\LoggerInterface::class
    //     => static fn (ContainerInterface $c) => $c->get(G\Debug\Collector\LogCollector::class),
    // With gadwinn/orm: replace its block above by this one
    // G\Orm\Contract\DbManagerInterface::class => static fn (ContainerInterface $c)
    //     => G\Orm\Core\DbManager::create(new G\Orm\Driver\LoggingConnection(
    //         (new G\Orm\Driver\Pdo\PdoDriver())->connect(G\Orm\Factory\OrmFactory::normalise($_ENV['DATABASE_URL'])),
    //         $c->get(G\Debug\Collector\QueryCollector::class),
    //     )),
];
