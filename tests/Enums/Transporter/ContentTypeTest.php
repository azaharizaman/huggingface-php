<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Enums\Transporter;

use AzahariZaman\Huggingface\Enums\Transporter\ContentType;
use PHPUnit\Framework\TestCase;

final class ContentTypeTest extends TestCase
{
    public function testJsonHasCorrectValue(): void
    {
        $this->assertSame('application/json', ContentType::JSON->value);
    }

    public function testMultipartHasCorrectValue(): void
    {
        $this->assertSame('multipart/form-data', ContentType::MULTIPART->value);
    }
}
