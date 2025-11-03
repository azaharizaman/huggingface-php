<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Responses\Inference\CreateResponseFilledMask;
use PHPUnit\Framework\TestCase;

final class CreateResponseFilledMaskTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $attributes = [
            'score' => 0.95,
            'token' => 123,
            'token_str' => 'test',
            'sequence' => 'This is a test sequence',
        ];

        $response = CreateResponseFilledMask::from($attributes);

        $this->assertInstanceOf(CreateResponseFilledMask::class, $response);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $attributes = [
            'score' => 0.95,
            'token' => 123,
            'token_str' => 'test',
            'sequence' => 'This is a test',
        ];

        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertSame([
            'score' => 0.95,
            'token' => 123,
            'token_str' => 'test',
            'sequence' => 'This is a test',
        ], $array);
    }

    public function testToArrayHasCorrectKeys(): void
    {
        $attributes = [
            'score' => 0.95,
            'token' => 123,
            'token_str' => 'test',
            'sequence' => 'Test sequence',
        ];
        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertArrayHasKey('score', $array);
        $this->assertArrayHasKey('token', $array);
        $this->assertArrayHasKey('token_str', $array);
        $this->assertArrayHasKey('sequence', $array);
        $this->assertSame(0.95, $array['score']);
        $this->assertSame(123, $array['token']);
        $this->assertSame('test', $array['token_str']);
        $this->assertSame('Test sequence', $array['sequence']);
    }
}
