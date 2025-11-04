<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Transporters;

use Closure;
use JsonException;
use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Exceptions\ErrorException;
use AzahariZaman\Huggingface\Exceptions\TransporterException;
use AzahariZaman\Huggingface\Exceptions\UnserializableResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;
use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
final class HttpTransporter implements TransporterContract
{
    /**
     * Creates a new Http Transporter instance.
     */
    public function __construct(
        private readonly ClientInterface $client,
        private readonly BaseUri $baseUri,
        private readonly Headers $headers,
        private readonly QueryParams $queryParams,
        private readonly Closure $streamHandler,
    ) {
        // ..
    }

    /**
     * {@inheritDoc}
     */
    public function requestObject(Payload $payload): array|string
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $clientException) {
            throw new TransporterException($clientException);
        }

        $contents = (string) $response->getBody();

        if ($response->getHeader('Content-Type')[0] === 'text/plain; charset=utf-8') {
            return $contents;
        }

        try {
            /** @var array{error?: array{message: string, type: string, code: string}} $response */
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException);
        }

        if (isset($response['error'])) {
            throw new ErrorException($response['error']);
        }

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function requestContent(Payload $payload): string
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $clientException) {
            throw new TransporterException($clientException);
        }

        $contents = $response->getBody()->getContents();

        try {
            /** @var array{error?: array{message: string, type: string, code: string}} $response */
            $response = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

            if (isset($response['error'])) {
                throw new ErrorException($response['error']);
            }
        } catch (JsonException) {
            // ..
        }

        return $contents;
    }

    /**
     * {@inheritDoc}
     */
    public function requestStream(Payload $payload): ResponseInterface
    {
        $request = $payload->toRequest($this->baseUri, $this->headers, $this->queryParams);

        try {
            $response = ($this->streamHandler)($request);
        } catch (ClientExceptionInterface $clientException) {
            throw new TransporterException($clientException);
        }

        return $response;
    }

    /**
     * Creates a new transporter instance with a different base URI.
     * This allows reusing the same client configuration for different API endpoints.
     */
    public function withBaseUri(BaseUri $baseUri): self
    {
        return new self(
            $this->client,
            $baseUri,
            $this->headers,
            $this->queryParams,
            $this->streamHandler
        );
    }
}
