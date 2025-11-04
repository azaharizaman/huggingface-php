<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\Hub;

use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: string, _id?: string, inference?: string, inferenceProviderMapping?: array}>
 */
final class ModelInfoResponse implements ResponseContract
{
    use ArrayAccessible;

    private function __construct(
        public readonly string $id,
        public readonly ?string $_id = null,
        public readonly ?string $inference = null,
        public readonly ?array $inferenceProviderMapping = null,
        public readonly ?array $tags = null,
        public readonly mixed $pipeline_tag = null,
        public readonly ?string $library_name = null,
        public readonly ?int $downloads = null,
        public readonly ?int $likes = null,
        public readonly ?string $author = null,
        public readonly ?string $sha = null,
        public readonly ?string $created_at = null,
        public readonly ?string $last_modified = null,
        public readonly ?bool $private = null,
        public readonly ?bool $gated = null,
        public readonly ?array $siblings = null,
        public readonly ?array $spaces = null,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  array $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['id'],
            $attributes['_id'] ?? null,
            $attributes['inference'] ?? null,
            $attributes['inferenceProviderMapping'] ?? null,
            $attributes['tags'] ?? null,
            $attributes['pipeline_tag'] ?? null,
            $attributes['library_name'] ?? null,
            $attributes['downloads'] ?? null,
            $attributes['likes'] ?? null,
            $attributes['author'] ?? null,
            $attributes['sha'] ?? null,
            $attributes['created_at'] ?? null,
            $attributes['last_modified'] ?? null,
            $attributes['private'] ?? null,
            $attributes['gated'] ?? null,
            $attributes['siblings'] ?? null,
            $attributes['spaces'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            '_id' => $this->_id,
            'inference' => $this->inference,
            'inferenceProviderMapping' => $this->inferenceProviderMapping,
            'tags' => $this->tags,
            'pipeline_tag' => $this->pipeline_tag,
            'library_name' => $this->library_name,
            'downloads' => $this->downloads,
            'likes' => $this->likes,
            'author' => $this->author,
            'sha' => $this->sha,
            'created_at' => $this->created_at,
            'last_modified' => $this->last_modified,
            'private' => $this->private,
            'gated' => $this->gated,
            'siblings' => $this->siblings,
            'spaces' => $this->spaces,
        ], fn($value) => $value !== null);
    }
}