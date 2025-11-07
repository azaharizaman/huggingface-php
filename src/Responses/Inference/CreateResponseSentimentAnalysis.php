<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Exceptions\InvalidArgumentException;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

final class CreateResponseSentimentAnalysis implements ResponseContract
{
    use ArrayAccessible;

    public function __construct(
        private readonly string $label,
        private readonly float $score
    ) {
        // ..
    }

    public static function from(array $attributes): self
    {
        // Support both the legacy nested shape [[{label, score}, ...]] and the new flat shape [{label, score}, ...]
        $entry = $attributes;

        if (isset($attributes[0]) && is_array($attributes[0]) && array_key_exists('label', $attributes[0])) {
            $entry = $attributes[0];
        }

        if (!array_key_exists('label', $entry)) {
            throw new InvalidArgumentException('Missing required field: label');
        }

        if (!array_key_exists('score', $entry)) {
            throw new InvalidArgumentException('Missing required field: score');
        }

        $label = (string) $entry['label'];
        $score = (float) $entry['score'];

        return new self($label, $score);
    }

    public function toArray(): array
    {
        return ['label' => $this->label, 'score' => $this->score];
    }
}
