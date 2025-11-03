<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Exceptions;

use AzahariZaman\Huggingface\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class InvalidArgumentExceptionTest extends TestCase
{
    public function testExceptionCanBeCreated(): void
    {
        $exception = new InvalidArgumentException('Invalid argument provided');
        
        $this->assertSame('Invalid argument provided', $exception->getMessage());
    }
}
