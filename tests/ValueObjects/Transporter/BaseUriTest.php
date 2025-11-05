<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects\Transporter;

use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;
use PHPUnit\Framework\TestCase;

final class BaseUriTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $baseUri = BaseUri::from('api.huggingface.co');

        $this->assertInstanceOf(BaseUri::class, $baseUri);
    }

    public function testToStringAddsHttpsProtocolAndTrailingSlash(): void
    {
        $baseUri = BaseUri::from('api.huggingface.co');

        $this->assertSame('https://api.huggingface.co/', $baseUri->toString());
    }

    public function testToStringWithHttpProtocol(): void
    {
        $baseUri = BaseUri::from('http://api.huggingface.co');

        $this->assertSame('http://api.huggingface.co/', $baseUri->toString());
    }

    public function testToStringWithHttpsProtocol(): void
    {
        $baseUri = BaseUri::from('https://api.huggingface.co');

        $this->assertSame('https://api.huggingface.co/', $baseUri->toString());
    }

    public function testToStringWithoutProtocol(): void
    {
        $baseUri = BaseUri::from('custom.domain.com');

        $this->assertSame('https://custom.domain.com/', $baseUri->toString());
    }

    public function testFromWithTrailingSlash(): void
    {
        $baseUri = BaseUri::from('api.example.com/');

        // BaseUri always appends a trailing slash, so this will have double slashes
        $this->assertSame('https://api.example.com//', $baseUri->toString());
    }

    public function testFromWithPath(): void
    {
        $baseUri = BaseUri::from('api.example.com/v1/api');

        $this->assertSame('https://api.example.com/v1/api/', $baseUri->toString());
    }

    public function testFromWithPort(): void
    {
        $baseUri = BaseUri::from('localhost:8080');

        $this->assertSame('https://localhost:8080/', $baseUri->toString());
    }

    public function testFromWithHttpAndPort(): void
    {
        $baseUri = BaseUri::from('http://localhost:3000');

        $this->assertSame('http://localhost:3000/', $baseUri->toString());
    }

    public function testFromWithSubdomain(): void
    {
        $baseUri = BaseUri::from('api.staging.example.com');

        $this->assertSame('https://api.staging.example.com/', $baseUri->toString());
    }
}
