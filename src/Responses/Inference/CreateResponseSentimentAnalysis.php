<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

final class CreateResponseSentimentAnalysis implements ResponseContract
{
    use ArrayAccessible;

    public function __construct(
        private readonly string $label,
        private readonly string $score
    ) {
        // ..
    }

    public static function from(array $attributes)
    {
        // dd($attributes[0]['label']);
        return new self($attributes[0]['label'], $attributes[0]['score']);
    }

    public function toArray(): array
    {
        return ['label' => $this->label, 'score' => $this->score];
    }
}
