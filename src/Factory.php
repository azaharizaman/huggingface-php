<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface;

use Closure;
use Exception;
use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Discovery\Psr18ClientDiscovery;
use Symfony\Component\HttpClient\Psr18Client;
use AzahariZaman\Huggingface\ValueObjects\ApiKey;
use AzahariZaman\Huggingface\Transporters\HttpTransporter;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;

final class Factory
{
    /**
     * The API key for the requests.
     */
    private ?string $apiKey = null;

    /**
     * The HTTP client for the requests.
     */
    private ?ClientInterface $httpClient = null;

    /**
     * The base URI for the requests.
     */
    private ?string $baseUri = null;

    /**
     * The HTTP headers for the requests.
     *
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * The query parameters for the requests.
     *
     * @var array<string, string|int>
     */
    private array $queryParams = [];

    private ?Closure $streamHandler = null;

    /**
     * Sets the API key for the requests.
     */
    public function withApiKey(string $apiKey): self
    {
        $this->apiKey = trim($apiKey);

        return $this;
    }

    /**
     * Sets the HTTP client for the requests.
     * If no client is provided the factory will try to find one using PSR-18 HTTP Client Discovery.
     */
    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Sets the stream handler for the requests. Not required when using Guzzle.
     */
    public function withStreamHandler(Closure $streamHandler): self
    {
        $this->streamHandler = $streamHandler;

        return $this;
    }

    /**
     * Sets the base URI for the requests.
     * If no URI is provided the factory will use the default Hugging Face API URI.
     */
    public function withBaseUri(string $baseUri): self
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Adds a custom HTTP header to the requests.
     */
    public function withHttpHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Adds a custom query parameter to the request url.
     */
    public function withQueryParam(string $name, string $value): self
    {
        $this->queryParams[$name] = $value;

        return $this;
    }

    /**
     * Creates a new Hugging Face AI Client.
     */
    public function make(): Client
    {
        $headers = Headers::create();

        if ($this->apiKey !== null) {
            $headers = Headers::withAuthorization(ApiKey::from($this->apiKey));
        }

        foreach ($this->headers as $name => $value) {
            $headers = $headers->withCustomHeader($name, $value);
        }

    // Default to inference API router for backward compatibility
    $baseUri = BaseUri::from($this->baseUri ?: 'router.huggingface.co/hf-inference');

        $queryParams = QueryParams::create();
        foreach ($this->queryParams as $name => $value) {
            $queryParams = $queryParams->withParam($name, $value);
        }

        $client = $this->httpClient ??= Psr18ClientDiscovery::find();

        $sendAsync = $this->makeStreamHandler($client);

        $transporter = new HttpTransporter($client, $baseUri, $headers, $queryParams, $sendAsync);

        return new Client($transporter);
    }

    /**
     * Creates a new Hugging Face AI Client configured for Chat Completions.
     */
    public function makeChatClient(): Client
    {
        $headers = Headers::create();

        if ($this->apiKey !== null) {
            $headers = Headers::withAuthorization(ApiKey::from($this->apiKey));
        }

        foreach ($this->headers as $name => $value) {
            $headers = $headers->withCustomHeader($name, $value);
        }

        // Use router endpoint for chat completions
        $baseUri = BaseUri::from($this->baseUri ?: 'router.huggingface.co');

        $queryParams = QueryParams::create();
        foreach ($this->queryParams as $name => $value) {
            $queryParams = $queryParams->withParam($name, $value);
        }

        $client = $this->httpClient ??= Psr18ClientDiscovery::find();

        $sendAsync = $this->makeStreamHandler($client);

        $transporter = new HttpTransporter($client, $baseUri, $headers, $queryParams, $sendAsync);

        return new Client($transporter);
    }

    /**
     * Creates a new stream handler for "stream" requests.
     */
    private function makeStreamHandler(ClientInterface $client): Closure
    {
        if (! is_null($this->streamHandler)) {
            return $this->streamHandler;
        }

        if ($client instanceof GuzzleClient) {
            return fn (RequestInterface $request): ResponseInterface => $client->send($request, ['stream' => true]);
        }

        if ($client instanceof Psr18Client) { // @phpstan-ignore-line
            return fn (RequestInterface $request): ResponseInterface => $client->sendRequest($request); // @phpstan-ignore-line
        }

        return function (RequestInterface $_): never {
            throw new Exception('To use stream requests you must provide an stream handler closure via the factory.');
        };
    }
}
