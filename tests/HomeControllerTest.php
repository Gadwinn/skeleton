<?php

declare(strict_types=1);

namespace App\Tests;

use G\Framework\Application;
use G\Framework\ApplicationFactory;
use G\Http\Message\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class HomeControllerTest extends TestCase
{
    private Application $app;

    protected function setUp(): void
    {
        $this->app = ApplicationFactory::fromDirectory(dirname(__DIR__));
    }

    private function request(string $method, string $uri): ResponseInterface
    {
        return $this->app->handle((new ServerRequestFactory())->createServerRequest($method, $uri));
    }

    public function testHome(): void
    {
        $response = $this->request('GET', '/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('text/html', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('href="/hello/world"', (string) $response->getBody());
    }

    public function testHello(): void
    {
        $response = $this->request('GET', '/hello/Ann');

        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertSame('{"message":"Hello Ann!"}', (string) $response->getBody());
    }

    public function testUnknownRoute(): void
    {
        $this->assertSame(404, $this->request('GET', '/unknown')->getStatusCode());
    }

    public function testWrongMethod(): void
    {
        $response = $this->request('POST', '/');

        $this->assertSame(405, $response->getStatusCode());
        $this->assertSame('GET, HEAD', $response->getHeaderLine('Allow'));
    }
}
