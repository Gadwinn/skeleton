<?php

declare(strict_types=1);

use App\Controller\HomeController;
use G\Router\RouterInterface;

return static function (RouterInterface $router): void {
    // $router->setBasePath('/G/skeleton/public');   // application served in a sub-folder
    $router->get('/', [HomeController::class, 'index'], ['name' => 'home']);
    $router->get('/hello/{name}', [HomeController::class, 'hello'], ['name' => 'hello']);
};
