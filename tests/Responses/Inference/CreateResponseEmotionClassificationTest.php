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

    public function testFromWithEmptyArray(): void
    {
        $attributes = [];

        $response = CreateResponseEmotionClassification::from($attributes);
        $array = $response->toArray();

        $this->assertInstanceOf(CreateResponseEmotionClassification::class, $response);
        $this->assertSame([], $array);
    }

    public function testFromWithSingleEmotion(): void
    {
        $attributes = [
            ['label' => 'happiness', 'score' => 1.0],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);
        $array = $response->toArray();

        $this->assertSame(['happiness' => 1.0], $array);
    }

    public function testFromWithMultipleEmotions(): void
    {
        $attributes = [
            ['label' => 'joy', 'score' => 0.5],
            ['label' => 'sadness', 'score' => 0.2],
            ['label' => 'anger', 'score' => 0.15],
            ['label' => 'fear', 'score' => 0.1],
            ['label' => 'surprise', 'score' => 0.05],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);
        $array = $response->toArray();

        $this->assertCount(5, $array);
        $this->assertArrayHasKey('joy', $array);
        $this->assertArrayHasKey('sadness', $array);
        $this->assertArrayHasKey('anger', $array);
        $this->assertArrayHasKey('fear', $array);
        $this->assertArrayHasKey('surprise', $array);
    }

    public function testArrayAccessOffsetExists(): void
    {
        $attributes = [
            ['label' => 'joy', 'score' => 0.9],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);

        $this->assertTrue(isset($response['joy']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testFromWithDecimalScores(): void
    {
        $attributes = [
            ['label' => 'neutral', 'score' => 0.12345],
            ['label' => 'positive', 'score' => 0.87655],
        ];

        $response = CreateResponseEmotionClassification::from($attributes);
        $array = $response->toArray();

        $this->assertSame(0.12345, $array['neutral']);
        $this->assertSame(0.87655, $array['positive']);
    }
}
