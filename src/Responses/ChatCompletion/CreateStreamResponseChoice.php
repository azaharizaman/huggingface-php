<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{index: int, delta: array{role?: string, content?: string}, finish_reason: string|null}>
 */
final class CreateStreamResponseChoice implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly int $index,
        public readonly CreateStreamResponseDelta $delta,
        public readonly ?string $finishReason = null,
    ) {
    }

    /**
     * @param  array{index: int, delta: array{role?: string, content?: string}, finish_reason?: string|null} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['index'],
            CreateStreamResponseDelta::from($attributes['delta']),
            $attributes['finish_reason'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'index' => $this->index,
            'delta' => $this->delta->toArray(),
            'finish_reason' => $this->finishReason,
        ];
    }
}
