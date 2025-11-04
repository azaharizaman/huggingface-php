<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{index: int, message: array{role: string, content: string}, finish_reason: string|null}>
 */
final class CreateResponseChoice implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly int $index,
        public readonly CreateResponseMessage $message,
        public readonly ?string $finishReason = null,
    ) {
    }

    /**
     * @param  array{index: int, message: array{role: string, content: string}, finish_reason?: string|null} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['index'],
            CreateResponseMessage::from($attributes['message']),
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
            'message' => $this->message->toArray(),
            'finish_reason' => $this->finishReason,
        ];
    }
}