<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Transporters;

use AzahariZaman\Huggingface\Exceptions\ErrorException;
use AzahariZaman\Huggingface\Exceptions\TransporterException;
use AzahariZaman\Huggingface\Exceptions\UnserializableResponse;
use AzahariZaman\Huggingface\Transporters\HttpTransporter;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;
use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class HttpTransporterTest extends TestCase
{
    public function testRequestObjectReturnsArray(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('{"result": "success"}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeader')->willReturn(['application/json']);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $payload = Payload::list('models');
        $result = $transporter->requestObject($payload);

        $this->assertIsArray($result);
        $this->assertSame(['result' => 'success'], $result);
    }

    public function testRequestObjectReturnsStringForTextPlain(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('Plain text response');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeader')->willReturn(['text/plain; charset=utf-8']);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $payload = Payload::list('models');
        $result = $transporter->requestObject($payload);

        $this->assertIsString($result);
        $this->assertSame('Plain text response', $result);
    }

    public function testRequestObjectThrowsErrorExceptionWhenErrorInResponse(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('{"error": {"message": "Error occurred", "type": "test_error", "code": "TEST_001"}}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeader')->willReturn(['application/json']);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Error occurred');

        $payload = Payload::list('models');
        $transporter->requestObject($payload);
    }

    public function testRequestObjectThrowsUnserializableResponseOnInvalidJson(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn('Invalid JSON {');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getHeader')->willReturn(['application/json']);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $this->expectException(UnserializableResponse::class);

        $payload = Payload::list('models');
        $transporter->requestObject($payload);
    }

    public function testRequestObjectThrowsTransporterExceptionOnClientException(): void
    {
        $clientException = new class ('Client error') extends \Exception implements ClientExceptionInterface {
        };

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willThrowException($clientException);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $this->createMock(ResponseInterface::class)
        );

        $this->expectException(TransporterException::class);

        $payload = Payload::list('models');
        $transporter->requestObject($payload);
    }

    public function testRequestContentReturnsString(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('Content string');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $payload = Payload::retrieveContent('files', 'file-id');
        $result = $transporter->requestContent($payload);

        $this->assertSame('Content string', $result);
    }

    public function testRequestContentThrowsErrorExceptionWhenErrorInResponse(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('{"error": {"message": "Error", "type": "error", "code": "ERR"}}');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $this->expectException(ErrorException::class);

        $payload = Payload::retrieveContent('files', 'file-id');
        $transporter->requestContent($payload);
    }

    public function testRequestContentIgnoresInvalidJson(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('Not valid JSON but that is OK');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willReturn($response);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $response
        );

        $payload = Payload::retrieveContent('files', 'file-id');
        $result = $transporter->requestContent($payload);

        $this->assertSame('Not valid JSON but that is OK', $result);
    }

    public function testRequestContentThrowsTransporterExceptionOnClientException(): void
    {
        $clientException = new class ('Client error') extends \Exception implements ClientExceptionInterface {
        };

        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')->willThrowException($clientException);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            fn(RequestInterface $request) => $this->createMock(ResponseInterface::class)
        );

        $this->expectException(TransporterException::class);

        $payload = Payload::retrieveContent('files', 'file-id');
        $transporter->requestContent($payload);
    }

    public function testRequestStreamReturnsResponse(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $client = $this->createMock(ClientInterface::class);

        $streamHandler = function (RequestInterface $request) use ($response): ResponseInterface {
            return $response;
        };

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            $streamHandler
        );

        $payload = Payload::create('models', ['input' => 'test']);
        $result = $transporter->requestStream($payload);

        $this->assertSame($response, $result);
    }

    public function testRequestStreamThrowsTransporterExceptionOnClientException(): void
    {
        $clientException = new class ('Client error') extends \Exception implements ClientExceptionInterface {
        };

        $streamHandler = function (RequestInterface $request) use ($clientException): never {
            throw $clientException;
        };

        $client = $this->createMock(ClientInterface::class);

        $transporter = new HttpTransporter(
            $client,
            BaseUri::from('api.test.com'),
            Headers::create(),
            QueryParams::create(),
            $streamHandler
        );

        $this->expectException(TransporterException::class);

        $payload = Payload::create('models', ['input' => 'test']);
        $transporter->requestStream($payload);
    }
}
