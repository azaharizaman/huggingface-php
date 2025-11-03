<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects\Transporter;

use AzahariZaman\Huggingface\Enums\Transporter\ContentType;
use AzahariZaman\Huggingface\ValueObjects\ApiKey;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Headers;
use PHPUnit\Framework\TestCase;

final class HeadersTest extends TestCase
{
    public function testCreateReturnsEmptyHeaders(): void
    {
        $headers = Headers::create();

        $this->assertInstanceOf(Headers::class, $headers);
        $this->assertSame([], $headers->toArray());
    }

    public function testWithAuthorizationAddsAuthHeader(): void
    {
        $apiKey = ApiKey::from('test-key');
        $headers = Headers::withAuthorization($apiKey);

        $this->assertInstanceOf(Headers::class, $headers);
        $this->assertSame([
            'Authorization' => 'Bearer test-key',
        ], $headers->toArray());
    }

    public function testWithContentTypeAddsContentTypeHeader(): void
    {
        $headers = Headers::create()->withContentType(ContentType::JSON);

        $this->assertSame([
            'Content-Type' => 'application/json',
        ], $headers->toArray());
    }

    public function testWithContentTypeAndSuffix(): void
    {
        $headers = Headers::create()->withContentType(ContentType::MULTIPART, '; boundary=test-boundary');

        $this->assertSame([
            'Content-Type' => 'multipart/form-data; boundary=test-boundary',
        ], $headers->toArray());
    }

    public function testWithCustomHeaderAddsHeader(): void
    {
        $headers = Headers::create()->withCustomHeader('X-Custom-Header', 'custom-value');

        $this->assertSame([
            'X-Custom-Header' => 'custom-value',
        ], $headers->toArray());
    }

    public function testHeadersAreImmutable(): void
    {
        $headers1 = Headers::create();
        $headers2 = $headers1->withCustomHeader('X-Test', 'value');

        $this->assertNotSame($headers1, $headers2);
        $this->assertSame([], $headers1->toArray());
        $this->assertSame(['X-Test' => 'value'], $headers2->toArray());
    }

    public function testMultipleHeadersCanBeAdded(): void
    {
        $headers = Headers::withAuthorization(ApiKey::from('key'))
            ->withContentType(ContentType::JSON)
            ->withCustomHeader('X-Custom', 'value');

        $this->assertSame([
            'Authorization' => 'Bearer key',
            'Content-Type' => 'application/json',
            'X-Custom' => 'value',
        ], $headers->toArray());
    }
}
