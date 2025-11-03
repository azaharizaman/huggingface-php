<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Inference;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

final class CreateResponseEmotionClassification implements ResponseContract
{
    use ArrayAccessible;

    public function __construct(
        private readonly array $results
    ) {
        // ..
    }

    public static function from(array $attributes)
    {
        return new self($attributes);
    }

    public function toArray(): array
    {
        $results = [];

        foreach ($this->results as $key => $result) {
            $results[$result['label']] = $result['score'];
        }

        return $results;
    }
}
