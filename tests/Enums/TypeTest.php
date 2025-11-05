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

    // Test new task types
    public function testAudioToAudioHasCorrectValue(): void
    {
        $this->assertSame('audio-to-audio', Type::AUDIO_TO_AUDIO->value);
    }

    public function testAutomaticSpeechRecognitionHasCorrectValue(): void
    {
        $this->assertSame('automatic-speech-recognition', Type::AUTOMATIC_SPEECH_RECOGNITION->value);
    }

    public function testAudioClassificationHasCorrectValue(): void
    {
        $this->assertSame('audio-classification', Type::AUDIO_CLASSIFICATION->value);
    }

    public function testImageToTextHasCorrectValue(): void
    {
        $this->assertSame('image-to-text', Type::IMAGE_TO_TEXT->value);
    }

    public function testImageTextToTextHasCorrectValue(): void
    {
        $this->assertSame('image-text-to-text', Type::IMAGE_TEXT_TO_TEXT->value);
    }

    public function testTextToSpeechHasCorrectValue(): void
    {
        $this->assertSame('text-to-speech', Type::TEXT_TO_SPEECH->value);
    }

    public function testTextToImageHasCorrectValue(): void
    {
        $this->assertSame('text-to-image', Type::TEXT_TO_IMAGE->value);
    }

    public function testImageToImageHasCorrectValue(): void
    {
        $this->assertSame('image-to-image', Type::IMAGE_TO_IMAGE->value);
    }

    public function testTranslationHasCorrectValue(): void
    {
        $this->assertSame('translation', Type::TRANSLATION->value);
    }

    public function testSentenceSimilarityHasCorrectValue(): void
    {
        $this->assertSame('sentence-similarity', Type::SENTENCE_SIMILARITY->value);
    }

    public function testConversationalHasCorrectValue(): void
    {
        $this->assertSame('conversational', Type::CONVERSATIONAL->value);
    }

    public function testChatCompletionHasCorrectValue(): void
    {
        $this->assertSame('chat-completion', Type::CHAT_COMPLETION->value);
    }

    public function testCanGetAllTypes(): void
    {
        $types = Type::cases();
        $this->assertCount(17, $types);

        $values = array_map(fn($case) => $case->value, $types);
        $this->assertContains('text-generation', $values);
        $this->assertContains('fill-mask', $values);
        $this->assertContains('summarization', $values);
        $this->assertContains('sentiment-analysis', $values);
        $this->assertContains('emotion-classification', $values);
        $this->assertContains('audio-to-audio', $values);
        $this->assertContains('automatic-speech-recognition', $values);
        $this->assertContains('audio-classification', $values);
        $this->assertContains('image-to-text', $values);
        $this->assertContains('image-text-to-text', $values);
        $this->assertContains('text-to-speech', $values);
        $this->assertContains('text-to-image', $values);
        $this->assertContains('image-to-image', $values);
        $this->assertContains('translation', $values);
        $this->assertContains('sentence-similarity', $values);
        $this->assertContains('conversational', $values);
        $this->assertContains('chat-completion', $values);
    }
}
