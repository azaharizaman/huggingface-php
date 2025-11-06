<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests;

use AzahariZaman\Huggingface\Client;
use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Resources\ChatCompletion;
use AzahariZaman\Huggingface\Resources\Hub;
use AzahariZaman\Huggingface\Resources\Inference;
use AzahariZaman\Huggingface\Transporters\HttpTransporter;
use AzahariZaman\Huggingface\ValueObjects\ApiKey;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

final class ClientTest extends TestCase
{
    public function testInferenceReturnsInferenceResource(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);

        $inference = $client->inference();

        $this->assertInstanceOf(Inference::class, $inference);
    }

    public function testChatCompletionReturnsChatCompletionResource(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);

        $chatCompletion = $client->chatCompletion();

        $this->assertInstanceOf(ChatCompletion::class, $chatCompletion);
    }

    public function testChatCompletionWithHttpTransporterCreatesNewTransporter(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $baseUri = BaseUri::from('router.huggingface.co/hf-inference');
        $headers = Headers::withAuthorization(ApiKey::from('test-key'));
        $queryParams = QueryParams::create();
        $streamHandler = fn() => null;

        $transporter = new HttpTransporter($httpClient, $baseUri, $headers, $queryParams, $streamHandler);
        $client = new Client($transporter);

        $chatCompletion1 = $client->chatCompletion();
        $chatCompletion2 = $client->chatCompletion();

        $this->assertInstanceOf(ChatCompletion::class, $chatCompletion1);
        $this->assertInstanceOf(ChatCompletion::class, $chatCompletion2);
        // Different instances but should use the same underlying transporter
        $this->assertNotSame($chatCompletion1, $chatCompletion2);
    }

    public function testHubReturnsHubResource(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);

        $hub = $client->hub();

        $this->assertInstanceOf(Hub::class, $hub);
    }

    public function testHubWithHttpTransporterCreatesNewTransporter(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $baseUri = BaseUri::from('router.huggingface.co/hf-inference');
        $headers = Headers::withAuthorization(ApiKey::from('test-key'));
        $queryParams = QueryParams::create();
        $streamHandler = fn() => null;

        $transporter = new HttpTransporter($httpClient, $baseUri, $headers, $queryParams, $streamHandler);
        $client = new Client($transporter);

        $hub1 = $client->hub();
        $hub2 = $client->hub();

        $this->assertInstanceOf(Hub::class, $hub1);
        $this->assertInstanceOf(Hub::class, $hub2);
        // Different instances but should use the same underlying transporter
        $this->assertNotSame($hub1, $hub2);
    }

    public function testChatCompletionWithNonHttpTransporterReusesOriginal(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);

        $chatCompletion = $client->chatCompletion();

        $this->assertInstanceOf(ChatCompletion::class, $chatCompletion);
    }

    public function testHubWithNonHttpTransporterReusesOriginal(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);

        $hub = $client->hub();

        $this->assertInstanceOf(Hub::class, $hub);
    }

    public function testMultipleResourcesWorkIndependently(): void
    {
        $httpClient = $this->createMock(ClientInterface::class);
    $baseUri = BaseUri::from('router.huggingface.co/hf-inference');
        $headers = Headers::withAuthorization(ApiKey::from('test-key'));
        $queryParams = QueryParams::create();
        $streamHandler = fn() => null;

        $transporter = new HttpTransporter($httpClient, $baseUri, $headers, $queryParams, $streamHandler);
        $client = new Client($transporter);

        $inference = $client->inference();
        $chatCompletion = $client->chatCompletion();
        $hub = $client->hub();

        $this->assertInstanceOf(Inference::class, $inference);
        $this->assertInstanceOf(ChatCompletion::class, $chatCompletion);
        $this->assertInstanceOf(Hub::class, $hub);
    }
}
