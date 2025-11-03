<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests;

use AzahariZaman\Huggingface\Client;
use AzahariZaman\Huggingface\Factory;
use GuzzleHttp\Client as GuzzleClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class FactoryTest extends TestCase
{
    public function testWithApiKey(): void
    {
        $factory = new Factory();
        $result = $factory->withApiKey('test-api-key');
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testWithApiKeyTrimsWhitespace(): void
    {
        $factory = new Factory();
        $client = $factory->withApiKey('  test-api-key  ')->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testWithHttpClient(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $factory = new Factory();
        $result = $factory->withHttpClient($httpClient);
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testWithBaseUri(): void
    {
        $factory = new Factory();
        $result = $factory->withBaseUri('custom.api.com');
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testWithHttpHeader(): void
    {
        $factory = new Factory();
        $result = $factory->withHttpHeader('X-Custom-Header', 'custom-value');
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testWithQueryParam(): void
    {
        $factory = new Factory();
        $result = $factory->withQueryParam('param', 'value');
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testWithStreamHandler(): void
    {
        $streamHandler = fn(RequestInterface $request): ResponseInterface => $this->createMock(ResponseInterface::class);
        $factory = new Factory();
        $result = $factory->withStreamHandler($streamHandler);
        
        $this->assertInstanceOf(Factory::class, $result);
        $this->assertSame($factory, $result);
    }

    public function testMakeCreatesClient(): void
    {
        $factory = new Factory();
        $client = $factory->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithAllOptions(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $factory = new Factory();
        $client = $factory
            ->withApiKey('test-key')
            ->withHttpClient($httpClient)
            ->withBaseUri('custom.api.com')
            ->withHttpHeader('X-Custom', 'value')
            ->withQueryParam('test', 'param')
            ->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithoutApiKey(): void
    {
        $factory = new Factory();
        $client = $factory->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithDefaultBaseUri(): void
    {
        $factory = new Factory();
        $client = $factory->withApiKey('test-key')->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithGuzzleClient(): void
    {
        $guzzleClient = new GuzzleClient();
        $factory = new Factory();
        $client = $factory->withHttpClient($guzzleClient)->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithCustomStreamHandler(): void
    {
        $streamHandler = function (RequestInterface $request): ResponseInterface {
            $response = $this->createMock(ResponseInterface::class);
            return $response;
        };
        
        $factory = new Factory();
        $client = $factory->withStreamHandler($streamHandler)->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithMultipleHeaders(): void
    {
        $factory = new Factory();
        $client = $factory
            ->withHttpHeader('X-Header-1', 'value-1')
            ->withHttpHeader('X-Header-2', 'value-2')
            ->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithMultipleQueryParams(): void
    {
        $factory = new Factory();
        $client = $factory
            ->withQueryParam('param1', 'value1')
            ->withQueryParam('param2', 'value2')
            ->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testMakeWithNonGuzzleClientAndNoStreamHandler(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $factory = new Factory();
        $client = $factory->withHttpClient($httpClient)->make();
        
        $this->assertInstanceOf(Client::class, $client);
    }
}
