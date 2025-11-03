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
}
