<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects\Transporter;

use AzahariZaman\Huggingface\Enums\Transporter\ContentType;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;
use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;
use PHPUnit\Framework\TestCase;

final class PayloadTest extends TestCase
{
    public function testListCreatesPayload(): void
    {
        $payload = Payload::list('models');
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testRetrieveCreatesPayload(): void
    {
        $payload = Payload::retrieve('models', 'model-id', '/files');
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testRetrieveContentCreatesPayload(): void
    {
        $payload = Payload::retrieveContent('files', 'file-id');
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testCreateCreatesPayload(): void
    {
        $payload = Payload::create('models', ['input' => 'test']);
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testUploadCreatesPayload(): void
    {
        $payload = Payload::upload('files', ['file' => 'test.txt']);
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testCancelCreatesPayload(): void
    {
        $payload = Payload::cancel('jobs', 'job-id');
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testDeleteCreatesPayload(): void
    {
        $payload = Payload::delete('models', 'model-id');
        
        $this->assertInstanceOf(Payload::class, $payload);
    }

    public function testToRequestWithJsonPayload(): void
    {
        $payload = Payload::create('models', ['input' => 'test', 'model' => 'gpt2']);
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('POST', $request->getMethod());
        $this->assertStringContainsString('api.test.com', (string) $request->getUri());
        $this->assertStringContainsString('models', (string) $request->getUri());
    }

    public function testToRequestWithQueryParams(): void
    {
        $payload = Payload::list('models');
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create()
            ->withParam('limit', 10)
            ->withParam('offset', 20);
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertStringContainsString('limit=10', (string) $request->getUri());
        $this->assertStringContainsString('offset=20', (string) $request->getUri());
    }

    public function testToRequestWithMultipartPayload(): void
    {
        $payload = Payload::upload('files', [
            'file' => 'test.txt',
            'name' => 'test',
        ]);
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('POST', $request->getMethod());
        $contentType = $request->getHeaderLine('Content-Type');
        $this->assertStringContainsString('multipart/form-data', $contentType);
        $this->assertStringContainsString('boundary=', $contentType);
    }

    public function testToRequestWithIntParameter(): void
    {
        $payload = Payload::upload('files', [
            'size' => 1024,
        ]);
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('POST', $request->getMethod());
    }

    public function testToRequestWithFloatParameter(): void
    {
        $payload = Payload::upload('files', [
            'temperature' => 0.7,
        ]);
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('POST', $request->getMethod());
    }

    public function testToRequestWithBoolParameter(): void
    {
        $payload = Payload::upload('files', [
            'enabled' => true,
            'disabled' => false,
        ]);
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('POST', $request->getMethod());
    }

    public function testToRequestWithGetMethod(): void
    {
        $payload = Payload::list('models');
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('', (string) $request->getBody());
    }

    public function testToRequestWithDeleteMethod(): void
    {
        $payload = Payload::delete('models', 'model-id');
        $baseUri = BaseUri::from('api.test.com');
        $headers = Headers::create();
        $queryParams = QueryParams::create();
        
        $request = $payload->toRequest($baseUri, $headers, $queryParams);
        
        $this->assertSame('DELETE', $request->getMethod());
    }
}
