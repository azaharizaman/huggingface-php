<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Hub;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array<int, array{id: string}>>
 */
final class ModelsListResponse implements ResponseContract
{
    use ArrayAccessible;

    /**
     * @param array<int, ModelInfoResponse> $models
     */
    private function __construct(
        public readonly array $models,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array $attributes
     */
    public static function from(array $attributes): self
    {
        $models = array_map(
            fn (array $model): ModelInfoResponse => ModelInfoResponse::from($model),
            $attributes
        );

        return new self($models);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_map(
            fn (ModelInfoResponse $model): array => $model->toArray(),
            $this->models
        );
    }
}
