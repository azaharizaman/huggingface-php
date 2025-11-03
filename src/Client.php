<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Resources\Inference;

final class Client implements Contracts\ClientContract
{
    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    /**
     * Inference
     */
    public function inference(): Inference
    {
        return new Inference($this->transporter);
    }
}
