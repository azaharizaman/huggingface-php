<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

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
}
