<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface;

final class Huggingface
{
    /**
     * Creates a new Hugging Face Client with the given API token.
     */
    public static function client(string $apiKey): Client
    {
        return self::factory()
            ->withApiKey($apiKey)
            ->make();
    }

    /**
     * Creates a new factory instance to configure a custom Hugging Face Client
     */
    public static function factory(): Factory
    {
        return new Factory();
    }
}
