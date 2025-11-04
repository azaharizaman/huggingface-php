<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Resources\Inference;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use PHPUnit\Framework\TestCase;

final class InferenceTest extends TestCase
{
    public function testConstructor(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $inference = new Inference($transporter);

        $this->assertInstanceOf(Inference::class, $inference);

        // Access the transporter property through reflection to ensure constructor is fully covered
        $reflection = new \ReflectionClass($inference);
        $transporterProperty = $reflection->getProperty('transporter');
        $transporterProperty->setAccessible(true);
        $actualTransporter = $transporterProperty->getValue($inference);

        $this->assertSame($transporter, $actualTransporter);
    }

    public function testCreateReturnsResponse(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                ['generated_text' => 'Test generated text'],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'gpt2',
            'type' => Type::TEXT_GENERATION,
            'inputs' => 'Test input',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithProvider(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                ['generated_text' => 'Test generated text'],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'gpt2',
            'type' => Type::TEXT_GENERATION,
            'inputs' => 'Test input',
            'provider' => Provider::TOGETHER,
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithAutoTypeDetection(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                ['generated_text' => 'Transcribed text'],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'openai/whisper-large',
            'inputs' => 'audio data',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithImageToTextModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'generated_text' => 'A cat sitting on a chair',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'Salesforce/blip-image-captioning-large',
            'inputs' => 'image data',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithTextToImageModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'image' => 'base64_image_data',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'runwayml/stable-diffusion-v1-5',
            'inputs' => 'A beautiful sunset over mountains',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithSentenceSimilarityModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [0.9, 0.3, 0.7],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'sentence-transformers/all-MiniLM-L6-v2',
            'inputs' => [
                'source_sentence' => 'This is an example sentence',
                'sentences' => ['This is a similar sentence', 'This is a different sentence']
            ],
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithTranslationModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'translation_text' => 'Hola mundo',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 't5-base',
            'inputs' => 'translate English to Spanish: Hello world',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithAllParameters(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                ['generated_text' => 'Generated response'],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'microsoft/DialoGPT-medium',
            'inputs' => 'Hello there',
            'type' => Type::TEXT_GENERATION,
            'provider' => Provider::SAMBANOVA,
            'parameters' => [
                'max_length' => 100,
                'temperature' => 0.7,
                'top_p' => 0.9,
            ],
            'options' => [
                'use_cache' => false,
                'wait_for_model' => true,
            ],
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithClipModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'generated_text' => 'A picture of a cat',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'openai/clip-vit-base-patch32',
            'inputs' => 'image data',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithFluxModel(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'image' => 'base64_image_data',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'black-forest-labs/flux-dev',
            'inputs' => 'A beautiful landscape',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithTranslationModelUnderscore(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'translation_text' => 'Bonjour le monde',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 't5_large',
            'inputs' => 'translate English to French: Hello world',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateWithTranslationModelByName(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'translation_text' => 'Hola mundo',
                ],
            ]);

        $inference = new Inference($transporter);

        $response = $inference->create([
            'model' => 'Helsinki-NLP/opus-mt-en-es-translation',
            'inputs' => 'Hello world',
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }
}
