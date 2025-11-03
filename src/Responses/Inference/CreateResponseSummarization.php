<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

final class CreateResponseSummarization implements ResponseContract
{
    use ArrayAccessible;

    public function __construct(private readonly string $summary_text)
    {
        // ..
    }

    public static function from(array $attributes)
    {
        return new self($attributes[0]['summary_text']);
    }

    public function toArray(): array
    {
        return ['summary_text' => $this->summary_text];
    }
}
