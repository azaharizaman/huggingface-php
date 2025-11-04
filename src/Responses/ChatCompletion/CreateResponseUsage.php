<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{prompt_tokens: int, completion_tokens: int, total_tokens: int}>
 */
final class CreateResponseUsage implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly int $promptTokens,
        public readonly int $completionTokens,
        public readonly int $totalTokens,
    ) {
    }

    /**
     * @param  array{prompt_tokens: int, completion_tokens: int, total_tokens: int} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['prompt_tokens'],
            $attributes['completion_tokens'],
            $attributes['total_tokens'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
        ];
    }
}
