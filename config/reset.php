<?php

declare(strict_types=1);

// Services to reset after each request: container id => function receiving the service.
//
// Only useful when one application serves several requests (FrankenPHP worker mode): a
// service keeping a state would hand it to the next request. A service that was not used
// during the request is skipped, never built just to be reset. In classic mode (PHP-FPM,
// Apache, built-in server) every request starts from scratch anyway.
return [
    // --- composer require gadwinn/orm ---------------------------------------------------------
    // Forget the entities loaded by the request (identity map) and its pending writes
    // G\Orm\Contract\DbManagerInterface::class
    //     => static fn (G\Orm\Contract\DbManagerInterface $orm) => $orm->unitOfWork()->clear(),

    // --- composer require gadwinn/translator --------------------------------------------------
    // Back to the default locale, when a request may change it
    // G\Translator\Runtime\Translator::class
    //     => static fn (G\Translator\Runtime\Translator $translator) => $translator->setLocale('fr-FR'),
];
