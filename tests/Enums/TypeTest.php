<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Enums;

use AzahariZaman\Huggingface\Enums\Type;
use PHPUnit\Framework\TestCase;

final class TypeTest extends TestCase
{
    public function testTextGenerationHasCorrectValue(): void
    {
        $this->assertSame('text-generation', Type::TEXT_GENERATION->value);
    }

    public function testFillMaskHasCorrectValue(): void
    {
        $this->assertSame('fill-mask', Type::FILL_MASK->value);
    }

    public function testSummarizationHasCorrectValue(): void
    {
        $this->assertSame('summarization', Type::SUMMARIZATION->value);
    }

    public function testSentimentAnalysisHasCorrectValue(): void
    {
        $this->assertSame('sentiment-analysis', Type::SENTIMENT_ANALYSIS->value);
    }

    public function testEmotionClassificationHasCorrectValue(): void
    {
        $this->assertSame('emotion-classification', Type::EMOTION_CLASSIFICATION->value);
    }
}
