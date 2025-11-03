<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Enums\Transporter;

use AzahariZaman\Huggingface\Enums\Transporter\Method;
use PHPUnit\Framework\TestCase;

final class MethodTest extends TestCase
{
    public function testGetHasCorrectValue(): void
    {
        $this->assertSame('GET', Method::GET->value);
    }

    public function testPostHasCorrectValue(): void
    {
        $this->assertSame('POST', Method::POST->value);
    }

    public function testPutHasCorrectValue(): void
    {
        $this->assertSame('PUT', Method::PUT->value);
    }

    public function testDeleteHasCorrectValue(): void
    {
        $this->assertSame('DELETE', Method::DELETE->value);
    }
}
