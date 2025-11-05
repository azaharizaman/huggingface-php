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

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'score' => 0.85,
            'token' => 456,
            'token_str' => 'word',
            'sequence' => 'Sample text',
        ];

        $response = CreateResponseFilledMask::from($attributes);

        $this->assertSame(0.85, $response['score']);
        $this->assertSame(456, $response['token']);
        $this->assertSame('word', $response['token_str']);
        $this->assertSame('Sample text', $response['sequence']);
        $this->assertTrue(isset($response['score']));
        $this->assertTrue(isset($response['token']));
        $this->assertTrue(isset($response['token_str']));
        $this->assertTrue(isset($response['sequence']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testWithDifferentScoreValues(): void
    {
        $attributes = [
            'score' => 0.12345,
            'token' => 999,
            'token_str' => 'token',
            'sequence' => 'Test',
        ];

        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertSame(0.12345, $array['score']);
        $this->assertIsFloat($array['score']);
    }

    public function testWithLongSequence(): void
    {
        $longSequence = 'This is a very long sequence with many words to test that the response handles long text properly without any issues.';
        $attributes = [
            'score' => 0.99,
            'token' => 1234,
            'token_str' => 'properly',
            'sequence' => $longSequence,
        ];

        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertSame($longSequence, $array['sequence']);
    }

    public function testWithSpecialCharactersInTokenStr(): void
    {
        $attributes = [
            'score' => 0.75,
            'token' => 789,
            'token_str' => '@#$%',
            'sequence' => 'Special characters test',
        ];

        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertSame('@#$%', $array['token_str']);
    }

    public function testWithZeroScore(): void
    {
        $attributes = [
            'score' => 0.0,
            'token' => 0,
            'token_str' => '',
            'sequence' => '',
        ];

        $response = CreateResponseFilledMask::from($attributes);
        $array = $response->toArray();

        $this->assertSame(0.0, $array['score']);
        $this->assertSame(0, $array['token']);
        $this->assertSame('', $array['token_str']);
        $this->assertSame('', $array['sequence']);
    }
}
