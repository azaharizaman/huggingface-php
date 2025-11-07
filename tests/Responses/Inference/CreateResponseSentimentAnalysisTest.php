<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Exceptions\InvalidArgumentException;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseSentimentAnalysis;
use PHPUnit\Framework\TestCase;

final class CreateResponseSentimentAnalysisTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $attributes = [
            ['label' => 'POSITIVE', 'score' => '0.95'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);

        $this->assertInstanceOf(CreateResponseSentimentAnalysis::class, $response);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $attributes = [
            ['label' => 'POSITIVE', 'score' => '0.95'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);
        $array = $response->toArray();

        $this->assertSame([
            'label' => 'POSITIVE',
            'score' => '0.95',
        ], $array);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            ['label' => 'NEGATIVE', 'score' => '0.85'],
        ];
        $response = CreateResponseSentimentAnalysis::from($attributes);

        $this->assertTrue(isset($response['label']));
        $this->assertSame('NEGATIVE', $response['label']);
        $this->assertSame('0.85', $response['score']);
    }

    public function testFromWithNeutralSentiment(): void
    {
        $attributes = [
            ['label' => 'NEUTRAL', 'score' => '0.50'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);
        $array = $response->toArray();

        $this->assertSame('NEUTRAL', $array['label']);
        $this->assertSame('0.50', $array['score']);
    }

    public function testFromWithHighConfidenceScore(): void
    {
        $attributes = [
            ['label' => 'POSITIVE', 'score' => '0.9999'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);
        $array = $response->toArray();

        $this->assertSame('POSITIVE', $array['label']);
        $this->assertSame('0.9999', $array['score']);
    }

    public function testFromWithLowConfidenceScore(): void
    {
        $attributes = [
            ['label' => 'NEGATIVE', 'score' => '0.0001'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);
        $array = $response->toArray();

        $this->assertSame('NEGATIVE', $array['label']);
        $this->assertSame('0.0001', $array['score']);
    }

    public function testArrayOffsetExists(): void
    {
        $attributes = [
            ['label' => 'POSITIVE', 'score' => '0.75'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);

        $this->assertTrue(isset($response['label']));
        $this->assertTrue(isset($response['score']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testToArrayStructure(): void
    {
        $attributes = [
            ['label' => 'NEGATIVE', 'score' => '0.65'],
        ];

        $response = CreateResponseSentimentAnalysis::from($attributes);
        $array = $response->toArray();

        $this->assertArrayHasKey('label', $array);
        $this->assertArrayHasKey('score', $array);
        $this->assertCount(2, $array);
    }

    public function testFromThrowsExceptionWhenLabelIsMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: label');

        $attributes = [
            ['score' => '0.95'],
        ];

        CreateResponseSentimentAnalysis::from($attributes);
    }

    public function testFromThrowsExceptionWhenScoreIsMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: score');

        $attributes = [
            ['label' => 'POSITIVE'],
        ];

        CreateResponseSentimentAnalysis::from($attributes);
    }

    public function testFromThrowsExceptionWhenBothFieldsAreMissing(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: label');

        $attributes = [
            [],
        ];

        CreateResponseSentimentAnalysis::from($attributes);
    }
}
