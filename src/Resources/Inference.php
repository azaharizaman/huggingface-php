<?php

namespace AzahariZaman\Huggingface\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;

class Inference
{
    /**
     * Creates a Client instance with the given API token.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    public function create(array $parameters)
    {
        $payload = Payload::create('models'.'/'.$parameters['model'], $parameters);

        $result = $this->transporter->requestObject($payload);

        return CreateResponse::from($result, $parameters['type']);
    }
}
