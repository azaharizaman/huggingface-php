<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;

final class Inference
{
    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    /**
     * Execute inference using the chosen model.
     *
     * @param array{
     *     model: string,
     *     inputs: string|array,
     *     type?: Type,
     *     provider?: Provider|string,
     *     parameters?: array<string, mixed>,
     *     data?: mixed,
     *     options?: array<string, mixed>
     * } $parameters
     */
    public function create(array $parameters): CreateResponse
    {
        // For backward compatibility, support both 'type' and auto-detection
        $type = $parameters['type'] ?? $this->detectTypeFromModel($parameters['model']);
        
        $payload = Payload::create('models/' . $parameters['model'], $parameters);

        $result = $this->transporter->requestObject($payload);

        return CreateResponse::from($result, $type);
    }

    /**
     * Detect the inference type from the model name or use a default.
     */
    private function detectTypeFromModel(string $model): Type
    {
        // Simple heuristics based on model name patterns
        if (str_contains($model, 'whisper')) {
            return Type::AUTOMATIC_SPEECH_RECOGNITION;
        }
        
        if (str_contains($model, 'stable-diffusion') || str_contains($model, 'flux')) {
            return Type::TEXT_TO_IMAGE;
        }
        
        if (str_contains($model, 'blip') || str_contains($model, 'clip')) {
            return Type::IMAGE_TO_TEXT;
        }
        
        if (str_contains($model, 'sentence-transformers')) {
            return Type::SENTENCE_SIMILARITY;
        }
        
        if (str_contains($model, 'translation') || str_contains($model, 't5')) {
            return Type::TRANSLATION;
        }
        
        // Default to text generation for chat/language models
        return Type::TEXT_GENERATION;
    }
}
