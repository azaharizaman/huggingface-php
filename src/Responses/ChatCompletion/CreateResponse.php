<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: string, object: string, created: int, model: string, choices: array<int, array{index: int, message: array{role: string, content: string}, finish_reason: string|null}>, usage: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}}>
 */
final class CreateResponse implements ResponseContract
{
    use ArrayAccessible;

    /**
     * @param array<int, CreateResponseChoice> $choices
     */
    private function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly int $created,
        public readonly string $model,
        public readonly array $choices,
        public readonly CreateResponseUsage $usage,
        public readonly ?string $systemFingerprint = null,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array{id: string, object: string, created: int, model: string, choices: array<int, array{index: int, message: array{role: string, content: string}, finish_reason: string|null}>, usage: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}, system_fingerprint?: string} $attributes
     */
    public static function from(array $attributes): self
    {
        $choices = array_map(
            fn (array $choice): CreateResponseChoice => CreateResponseChoice::from($choice),
            $attributes['choices']
        );

        $usage = CreateResponseUsage::from($attributes['usage']);

        return new self(
            $attributes['id'],
            $attributes['object'],
            $attributes['created'],
            $attributes['model'],
            $choices,
            $usage,
            $attributes['system_fingerprint'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'created' => $this->created,
            'model' => $this->model,
            'choices' => array_map(
                fn (CreateResponseChoice $choice): array => $choice->toArray(),
                $this->choices
            ),
            'usage' => $this->usage->toArray(),
            'system_fingerprint' => $this->systemFingerprint,
        ];
    }
}
