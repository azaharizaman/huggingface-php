<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseEmotionClassification;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseFilledMask;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseGeneratedText;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseSentimentAnalysis;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponseSummarization;
use PHPUnit\Framework\TestCase;

final class CreateResponseTest extends TestCase
{
    public function testFromWithTextGeneration(): void
    {
        $attributes = [
            ['generated_text' => 'Generated text content'],
        ];

        $response = CreateResponse::from($attributes, Type::TEXT_GENERATION);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::TEXT_GENERATION, $response->type);
        $this->assertInstanceOf(CreateResponseGeneratedText::class, $response->generatedText);
        $this->assertNull($response->summarization);
        $this->assertEmpty($response->sentimentAnalysis);
        $this->assertEmpty($response->filledMasks);
        $this->assertEmpty($response->emotionClassification);
    }

    public function testFromWithSummarization(): void
    {
        $attributes = [
            ['summary_text' => 'Summary content'],
        ];

        $response = CreateResponse::from($attributes, Type::SUMMARIZATION);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::SUMMARIZATION, $response->type);
        $this->assertInstanceOf(CreateResponseSummarization::class, $response->summarization);
        $this->assertNull($response->generatedText);
    }

    public function testFromWithFillMask(): void
    {
        $attributes = [
            [
                'score' => 0.95,
                'token' => 123,
                'token_str' => 'test',
                'sequence' => 'This is a test sequence',
            ],
            [
                'score' => 0.85,
                'token' => 456,
                'token_str' => 'sample',
                'sequence' => 'This is a sample sequence',
            ],
        ];

        $response = CreateResponse::from($attributes, Type::FILL_MASK);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::FILL_MASK, $response->type);
        $this->assertCount(2, $response->filledMasks);
        $this->assertInstanceOf(CreateResponseFilledMask::class, $response->filledMasks[0]);
        $this->assertInstanceOf(CreateResponseFilledMask::class, $response->filledMasks[1]);
    }

    public function testFromWithSentimentAnalysis(): void
    {
        $attributes = [
            [
                ['label' => 'POSITIVE', 'score' => '0.95'],
            ],
            [
                ['label' => 'NEGATIVE', 'score' => '0.05'],
            ],
        ];

        $response = CreateResponse::from($attributes, Type::SENTIMENT_ANALYSIS);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::SENTIMENT_ANALYSIS, $response->type);
        $this->assertCount(2, $response->sentimentAnalysis);
        $this->assertInstanceOf(CreateResponseSentimentAnalysis::class, $response->sentimentAnalysis[0]);
        $this->assertInstanceOf(CreateResponseSentimentAnalysis::class, $response->sentimentAnalysis[1]);
    }

    public function testFromWithEmotionClassification(): void
    {
        $attributes = [
            [
                ['label' => 'joy', 'score' => 0.9],
                ['label' => 'sadness', 'score' => 0.1],
            ],
            [
                ['label' => 'anger', 'score' => 0.8],
                ['label' => 'fear', 'score' => 0.2],
            ],
        ];

        $response = CreateResponse::from($attributes, Type::EMOTION_CLASSIFICATION);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::EMOTION_CLASSIFICATION, $response->type);
        $this->assertCount(2, $response->emotionClassification);
        $this->assertInstanceOf(CreateResponseEmotionClassification::class, $response->emotionClassification[0]);
        $this->assertInstanceOf(CreateResponseEmotionClassification::class, $response->emotionClassification[1]);
    }

    public function testToArrayWithTextGeneration(): void
    {
        $attributes = [['generated_text' => 'Test text']];
        $response = CreateResponse::from($attributes, Type::TEXT_GENERATION);

        $array = $response->toArray();

        $this->assertSame(Type::TEXT_GENERATION, $array['type']);
        $this->assertSame('Test text', $array['generated_text']);
    }

    public function testToArrayWithSummarization(): void
    {
        $attributes = [['summary_text' => 'Test summary']];
        $response = CreateResponse::from($attributes, Type::SUMMARIZATION);

        $array = $response->toArray();

        $this->assertSame(Type::SUMMARIZATION, $array['type']);
        $this->assertSame('Test summary', $array['summary_text']);
    }

    public function testToArrayWithFillMask(): void
    {
        $attributes = [
            [
                'score' => 0.95,
                'token' => 123,
                'token_str' => 'test',
                'sequence' => 'Test sequence',
            ],
        ];
        $response = CreateResponse::from($attributes, Type::FILL_MASK);

        $array = $response->toArray();

        $this->assertSame(Type::FILL_MASK, $array['type']);
        $this->assertArrayHasKey('filled_masks', $array);
        $this->assertCount(1, $array['filled_masks']);
    }

    public function testToArrayWithSentimentAnalysis(): void
    {
        $attributes = [
            [['label' => 'POSITIVE', 'score' => '0.95']],
        ];
        $response = CreateResponse::from($attributes, Type::SENTIMENT_ANALYSIS);

        $array = $response->toArray();

        $this->assertSame(Type::SENTIMENT_ANALYSIS, $array['type']);
        $this->assertArrayHasKey('sentiment_analysis', $array);
        $this->assertCount(1, $array['sentiment_analysis']);
    }

    public function testToArrayWithEmotionClassification(): void
    {
        $attributes = [
            [
                ['label' => 'joy', 'score' => 0.9],
            ],
        ];
        $response = CreateResponse::from($attributes, Type::EMOTION_CLASSIFICATION);

        $array = $response->toArray();

        $this->assertSame(Type::EMOTION_CLASSIFICATION, $array['type']);
        $this->assertArrayHasKey('emotion_classification', $array);
        $this->assertCount(1, $array['emotion_classification']);
    }

    public function testArrayAccess(): void
    {
        $attributes = [['generated_text' => 'Test']];
        $response = CreateResponse::from($attributes, Type::TEXT_GENERATION);

        $this->assertTrue(isset($response['type']));
        $this->assertSame(Type::TEXT_GENERATION, $response['type']);
    }

    public function testFromWithDefaultType(): void
    {
        $attributes = ['custom_data' => 'some value', 'result' => 42];

        // Use an unsupported type that will trigger the default case
        $response = CreateResponse::from($attributes, Type::AUTOMATIC_SPEECH_RECOGNITION);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame(Type::AUTOMATIC_SPEECH_RECOGNITION, $response->type);
        $this->assertNull($response->generatedText);
        $this->assertNull($response->summarization);
        $this->assertEmpty($response->sentimentAnalysis);
        $this->assertEmpty($response->filledMasks);
        $this->assertEmpty($response->emotionClassification);
        $this->assertSame($attributes, $response->rawResponse);
    }

    public function testToArrayWithDefaultType(): void
    {
        $attributes = ['custom_data' => 'some value', 'result' => 42];

        $response = CreateResponse::from($attributes, Type::AUTOMATIC_SPEECH_RECOGNITION);
        $array = $response->toArray();

        $this->assertSame(Type::AUTOMATIC_SPEECH_RECOGNITION, $array['type']);
        $this->assertSame('some value', $array['custom_data']);
        $this->assertSame(42, $array['result']);
    }

    public function testToArrayWithDefaultTypeAndNullRawResponse(): void
    {
        // Create response without attributes to trigger null rawResponse case
        $response = new \ReflectionClass(CreateResponse::class);
        $constructor = $response->getConstructor();
        $instance = $response->newInstanceWithoutConstructor();
        
        $typeProperty = $response->getProperty('type');
        $typeProperty->setAccessible(true);
        $typeProperty->setValue($instance, Type::IMAGE_TO_TEXT);
        
        $rawResponseProperty = $response->getProperty('rawResponse');
        $rawResponseProperty->setAccessible(true);
        $rawResponseProperty->setValue($instance, null);

        $array = $instance->toArray();

        $this->assertSame(Type::IMAGE_TO_TEXT, $array['type']);
        $this->assertCount(1, $array); // Only type should be present
    }
}
