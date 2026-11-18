<?php

declare(strict_types=1);

use G\Router\Attribute\AttributeLoader;
use G\Router\RouterInterface;

return static function (RouterInterface $router): void {
    // $router->setBasePath('/G/skeleton/public');   // application served in a sub-folder

    // Routes declared with the #[Group] and #[Route] attributes of the controllers
    (new AttributeLoader($router))->loadDirectory(dirname(__DIR__) . '/src/Controller', 'App\Controller');

    // Routes can also be mapped here
    // $router->get('/ping', static fn (): string => 'pong');
};
