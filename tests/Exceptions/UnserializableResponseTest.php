<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Exceptions;

use AzahariZaman\Huggingface\Exceptions\UnserializableResponse;
use JsonException;
use PHPUnit\Framework\TestCase;

final class UnserializableResponseTest extends TestCase
{
    public function testConstructWithJsonException(): void
    {
        $jsonException = new JsonException('Invalid JSON');

        $exception = new UnserializableResponse($jsonException);

        $this->assertSame('Invalid JSON', $exception->getMessage());
        $this->assertSame($jsonException, $exception->getPrevious());
    }
}
