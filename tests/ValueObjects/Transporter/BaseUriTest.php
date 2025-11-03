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
}
