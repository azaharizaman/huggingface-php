<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Responses\Inference\CreateResponseGeneratedText;
use PHPUnit\Framework\TestCase;

final class CreateResponseGeneratedTextTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $attributes = [
            ['generated_text' => 'Generated text content'],
        ];

        $response = CreateResponseGeneratedText::from($attributes);

        $this->assertInstanceOf(CreateResponseGeneratedText::class, $response);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $attributes = [
            ['generated_text' => 'Test generated text'],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertSame(['generated_text' => 'Test generated text'], $array);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            ['generated_text' => 'Test text'],
        ];
        $response = CreateResponseGeneratedText::from($attributes);

        $this->assertTrue(isset($response['generated_text']));
        $this->assertSame('Test text', $response['generated_text']);
    }

    public function testFromWithLongGeneratedText(): void
    {
        $longText = str_repeat('This is a long generated text. ', 100);
        $attributes = [
            ['generated_text' => $longText],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertSame($longText, $array['generated_text']);
        $this->assertStringContainsString('This is a long generated text.', $array['generated_text']);
    }

    public function testFromWithEmptyGeneratedText(): void
    {
        $attributes = [
            ['generated_text' => ''],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertSame('', $array['generated_text']);
    }

    public function testFromWithMultilineGeneratedText(): void
    {
        $multilineText = "Line 1\nLine 2\nLine 3\nLine 4";
        $attributes = [
            ['generated_text' => $multilineText],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertSame($multilineText, $array['generated_text']);
        $this->assertStringContainsString("\n", $array['generated_text']);
    }

    public function testFromWithSpecialCharacters(): void
    {
        $specialText = 'Text with special chars: @#$%^&*()[]{}';
        $attributes = [
            ['generated_text' => $specialText],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertSame($specialText, $array['generated_text']);
    }

    public function testArrayOffsetExists(): void
    {
        $attributes = [
            ['generated_text' => 'Sample text'],
        ];

        $response = CreateResponseGeneratedText::from($attributes);

        $this->assertTrue(isset($response['generated_text']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testToArrayStructure(): void
    {
        $attributes = [
            ['generated_text' => 'Test output'],
        ];

        $response = CreateResponseGeneratedText::from($attributes);
        $array = $response->toArray();

        $this->assertArrayHasKey('generated_text', $array);
        $this->assertCount(1, $array);
    }
}
