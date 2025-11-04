<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Responses\Hub\ModelInfoResponse;
use AzahariZaman\Huggingface\Responses\Hub\ModelsListResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;

final class Hub
{
    /**
     * Creates a Hub instance with the given transporter.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    /**
     * Get model information.
     *
     * @param array{
     *     model_id: string,
     *     expand?: array<string>
     * } $parameters
     */
    public function getModel(array $parameters): ModelInfoResponse
    {
        $payload = Payload::retrieve('api/models', $parameters['model_id']);

        $result = $this->transporter->requestObject($payload);

        return ModelInfoResponse::from($result);
    }

    /**
     * List models with optional filtering.
     *
     * @param array{
     *     inference_provider?: Provider|string,
     *     pipeline_tag?: string,
     *     search?: string,
     *     author?: string,
     *     filter?: string,
     *     sort?: string,
     *     direction?: string,
     *     limit?: int,
     *     full?: bool
     * } $parameters
     */
    public function listModels(array $parameters = []): ModelsListResponse
    {
        $payload = Payload::list('api/models');

        $result = $this->transporter->requestObject($payload);

        return ModelsListResponse::from($result);
    }

    /**
     * Get the authenticated user information.
     */
    public function whoami(): array
    {
        $payload = Payload::retrieve('api', 'whoami-v2');

        $result = $this->transporter->requestObject($payload);

        return is_array($result) ? $result : [];
    }
}