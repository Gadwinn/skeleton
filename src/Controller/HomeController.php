<?php

declare(strict_types=1);

namespace App\Controller;

use G\Router\RouterInterface;

/**
 * No parent class: dependencies come through the constructor, route parameters by name.
 */
final class HomeController
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    /**
     * A string is sent as HTML.
     */
    public function index(): string
    {
        $hello = htmlspecialchars($this->router->url('hello', ['name' => 'world']), ENT_QUOTES);

        return <<<HTML
            <!doctype html>
            <html lang="en">
            <head><meta charset="utf-8"><title>gadwinn/skeleton</title></head>
            <body>
                <h1>It works!</h1>
                <p>Try <a href="{$hello}">{$hello}</a>.</p>
            </body>
            </html>
            HTML;
    }

    /**
     * An array is sent as JSON; {name} is injected in $name.
     *
     * @return array{message: string}
     */
    public function hello(string $name): array
    {
        return ['message' => "Hello {$name}!"];
    }
}
