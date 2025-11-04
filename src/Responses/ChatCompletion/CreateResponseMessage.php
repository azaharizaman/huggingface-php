<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{role: string, content: string}>
 */
final class CreateResponseMessage implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly string $role,
        public readonly string $content,
        public readonly ?array $toolCalls = null,
    ) {
    }

    /**
     * @param  array{role: string, content: string, tool_calls?: array} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['role'],
            $attributes['content'],
            $attributes['tool_calls'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = [
            'role' => $this->role,
            'content' => $this->content,
        ];

        if ($this->toolCalls !== null) {
            $array['tool_calls'] = $this->toolCalls;
        }

        return $array;
    }
}
