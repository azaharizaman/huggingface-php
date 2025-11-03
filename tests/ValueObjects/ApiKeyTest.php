<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects;

use AzahariZaman\Huggingface\ValueObjects\ApiKey;
use PHPUnit\Framework\TestCase;

final class ApiKeyTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $apiKey = ApiKey::from('test-api-key');

        $this->assertInstanceOf(ApiKey::class, $apiKey);
    }

    public function testToStringReturnsApiKey(): void
    {
        $apiKey = ApiKey::from('test-api-key');

        $this->assertSame('test-api-key', $apiKey->toString());
    }

    public function testApiKeyPropertyIsAccessible(): void
    {
        $apiKey = ApiKey::from('test-api-key');

        $this->assertSame('test-api-key', $apiKey->apiKey);
    }
}
