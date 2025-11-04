<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{role?: string, content?: string}>
 */
final class CreateStreamResponseDelta implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly ?string $role = null,
        public readonly ?string $content = null,
    ) {
    }

    /**
     * @param  array{role?: string, content?: string} $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['role'] ?? null,
            $attributes['content'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $array = [];
        
        if ($this->role !== null) {
            $array['role'] = $this->role;
        }
        
        if ($this->content !== null) {
            $array['content'] = $this->content;
        }

        return $array;
    }
}