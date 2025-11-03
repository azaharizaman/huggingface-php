<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

final class CreateResponseFilledMask implements ResponseContract
{
    use ArrayAccessible;

    public function __construct(
        private readonly float $score,
        private readonly int $token,
        private readonly string $tokenStr,
        private readonly string $sequence,
    ) {
        // ..
    }

    public static function from(array $attributes)
    {
        return new self(
            $attributes['score'],
            $attributes['token'],
            $attributes['token_str'],
            $attributes['sequence'],
        );
    }

    public function toArray(): array
    {
        return [
            'score' => $this->score,
            'token' => $this->token,
            'token_str' => $this->tokenStr,
            'sequence' => $this->sequence,
        ];
    }
}
