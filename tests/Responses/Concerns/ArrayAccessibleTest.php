<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Concerns;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

final class ArrayAccessibleTest extends TestCase
{
    private ResponseContract $response;

    protected function setUp(): void
    {
        $this->response = new class implements ResponseContract {
            use ArrayAccessible;

            public function toArray(): array
            {
                return ['key1' => 'value1', 'key2' => 'value2'];
            }
        };
    }

    public function testOffsetExists(): void
    {
        $this->assertTrue(isset($this->response['key1']));
        $this->assertTrue(isset($this->response['key2']));
        $this->assertFalse(isset($this->response['key3']));
    }

    public function testOffsetGet(): void
    {
        $this->assertSame('value1', $this->response['key1']);
        $this->assertSame('value2', $this->response['key2']);
    }

    public function testOffsetSetThrowsException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Cannot set response attributes.');
        
        $this->response['key1'] = 'new value';
    }

    public function testOffsetUnsetThrowsException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Cannot unset response attributes.');
        
        unset($this->response['key1']);
    }
}
