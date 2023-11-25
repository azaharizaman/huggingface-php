<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: string, object: string, created: int, model: string, choices: array<int, array{text: string, index: int, logprobs: array{tokens: array<int, string>, token_logprobs: array<int, float>, top_logprobs: array<int, string>|null, text_offset: array<int, int>}|null, finish_reason: string|null}>, usage: array{prompt_tokens: int, completion_tokens: int|null, total_tokens: int}}>
 */
final class CreateResponse implements ResponseContract
{

    use ArrayAccessible;

    private function __construct(
        public readonly Type $type,
        public ?CreateResponseGeneratedText $generatedText=null,
        public ?CreateResponseSummarization $summarization=null,
        public array $sentimentAnalysis=[],
        public array $filledMasks=[],
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array  $attributes
     * @param  Type $type
     */
    public static function from(array $attributes, Type $type): self
    {
        $self = new self($type);
        switch ($type->value) {
            case Type::TEXT_GENERATION->value:
                $self->generatedText = CreateResponseGeneratedText::from($attributes);
                return $self;
            case Type::FILL_MASK->value:
                $self->filledMasks = array_map(fn (array $result): CreateResponseFilledMask => CreateResponseFilledMask::from(
                    $result
                ), $attributes);
                return $self;
            case Type::SUMMARIZATION->value:
                $self->summarization = CreateResponseSummarization::from($attributes);
                return $self;
            case Type::SENTIMENT_ANALYSIS->value:
                $self->sentimentAnalysis = array_map(fn (array $result): CreateResponseSentimentAnalysis => CreateResponseSentimentAnalysis::from(
                    $result
                ), $attributes);
                return $self;
        }

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->type,
        ];

        switch ($this->type->value) {
            case Type::TEXT_GENERATION->value:
                $array['generated_text'] = $this->generatedText->toArray()['generated_text'];
                break;
            case Type::FILL_MASK->value:
                $array['filled_masks'] = array_map(fn (CreateResponseFilledMask $filledMask): array => $filledMask->toArray(), $this->filledMasks);
                break;
            case Type::SUMMARIZATION->value:
                $array['summary_text'] = $this->summarization->toArray()['summary_text'];
                break;
            case Type::SENTIMENT_ANALYSIS->value:
                $array['sentiment_analysis'] = array_map(fn (CreateResponseSentimentAnalysis $sentimentAnalysis): array => $sentimentAnalysis->toArray(), $this->sentimentAnalysis);
                break;
        }

        return $array;
    }
}
