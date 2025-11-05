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

    public function testFromWithEmptyString(): void
    {
        $apiKey = ApiKey::from('');

        $this->assertInstanceOf(ApiKey::class, $apiKey);
        $this->assertSame('', $apiKey->toString());
        $this->assertSame('', $apiKey->apiKey);
    }

    public function testFromWithLongApiKey(): void
    {
        $longKey = 'hf_' . str_repeat('abcdefghijklmnopqrstuvwxyz0123456789', 10);
        $apiKey = ApiKey::from($longKey);

        $this->assertSame($longKey, $apiKey->toString());
        $this->assertSame($longKey, $apiKey->apiKey);
    }

    public function testFromWithSpecialCharacters(): void
    {
        $specialKey = 'hf_test-key_123.456@special';
        $apiKey = ApiKey::from($specialKey);

        $this->assertSame($specialKey, $apiKey->toString());
        $this->assertSame($specialKey, $apiKey->apiKey);
    }

    public function testApiKeyIsReadonly(): void
    {
        $apiKey = ApiKey::from('test-key');

        // Test that the property can be accessed (testing readonly nature at compile time)
        $this->assertIsString($apiKey->apiKey);
        $this->assertSame('test-key', $apiKey->apiKey);
    }

    public function testMultipleInstancesAreIndependent(): void
    {
        $apiKey1 = ApiKey::from('key-one');
        $apiKey2 = ApiKey::from('key-two');

        $this->assertNotSame($apiKey1->apiKey, $apiKey2->apiKey);
        $this->assertSame('key-one', $apiKey1->apiKey);
        $this->assertSame('key-two', $apiKey2->apiKey);
    }
}
