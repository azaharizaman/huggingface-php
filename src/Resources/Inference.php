<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;

final class Inference
{
    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    /**
     * Execute inference using the chosen model.
     *
     * @param array{model: string, inputs: string, type: Type, parameters?: array<string, mixed>} $parameters
     */
    public function create(array $parameters): CreateResponse
    {
        $payload = Payload::create('models/' . $parameters['model'], $parameters);

        $result = $this->transporter->requestObject($payload);

        return CreateResponse::from($result, $parameters['type']);
    }
}
