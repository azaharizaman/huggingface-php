<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Exceptions;

use AzahariZaman\Huggingface\Exceptions\ErrorException;
use PHPUnit\Framework\TestCase;

final class ErrorExceptionTest extends TestCase
{
    public function testConstructWithArrayContents(): void
    {
        $contents = [
            'message' => 'Test error message',
            'type' => 'test_error',
            'code' => 'TEST_001',
        ];
        
        $exception = new ErrorException($contents);
        
        $this->assertSame('Test error message', $exception->getMessage());
        $this->assertSame('Test error message', $exception->getErrorMessage());
        $this->assertSame('test_error', $exception->getErrorType());
        $this->assertSame('TEST_001', $exception->getErrorCode());
    }

    public function testConstructWithStringContents(): void
    {
        $exception = new ErrorException('Simple error message');
        
        $this->assertSame('Simple error message', $exception->getMessage());
        $this->assertSame('Simple error message', $exception->getErrorMessage());
    }

    public function testConstructWithStringOnlyAffectsMessage(): void
    {
        $exception = new ErrorException('Simple error');
        
        $this->assertSame('Simple error', $exception->getMessage());
        $this->assertSame('Simple error', $exception->getErrorMessage());
        // Note: getErrorType() and getErrorCode() should not be called when constructed with string
    }

    public function testConstructWithArrayWithoutType(): void
    {
        $contents = [
            'message' => 'Error without type',
            'type' => null,
            'code' => null,
        ];
        
        $exception = new ErrorException($contents);
        
        $this->assertSame('Error without type', $exception->getMessage());
        $this->assertNull($exception->getErrorType());
        $this->assertNull($exception->getErrorCode());
    }
}
