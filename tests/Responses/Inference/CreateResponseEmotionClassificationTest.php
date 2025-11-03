<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Responses\Inference\CreateResponseEmotionClassification;
use PHPUnit\Framework\TestCase;

final class CreateResponseEmotionClassificationTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $attributes = [
            ['label' => 'joy', 'score' => 0.9],
            ['label' => 'sadness', 'score' => 0.1],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);

        $this->assertInstanceOf(CreateResponseEmotionClassification::class, $response);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $attributes = [
            ['label' => 'joy', 'score' => 0.9],
            ['label' => 'sadness', 'score' => 0.1],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);
        $array = $response->toArray();

        $this->assertSame([
            'joy' => 0.9,
            'sadness' => 0.1,
        ], $array);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            ['label' => 'anger', 'score' => 0.8],
            ['label' => 'fear', 'score' => 0.2],
        ];
        $response = CreateResponseEmotionClassification::from($attributes);

        $array = $response->toArray();
        $this->assertTrue(isset($response['anger']));
        $this->assertSame(0.8, $response['anger']);
        $this->assertSame(0.2, $response['fear']);
    }
}
