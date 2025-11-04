<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;

final class ChatCompletion
{
    /**
     * Creates a ChatCompletion instance with the given transporter.
     */
    public function __construct(private readonly TransporterContract $transporter)
    {
        // ..
    }

    /**
     * Create a chat completion request.
     *
     * @param array{
     *     model: string,
     *     messages: array<array{role: string, content: string|array}>,
     *     provider?: Provider|string,
     *     max_tokens?: int,
     *     temperature?: float,
     *     top_p?: float,
     *     frequency_penalty?: float,
     *     presence_penalty?: float,
     *     stop?: string|array<string>,
     *     stream?: bool,
     *     tools?: array,
     *     tool_choice?: string|array,
     *     response_format?: array,
     *     seed?: int,
     *     logprobs?: bool,
     *     top_logprobs?: int
     * } $parameters
     */
    public function create(array $parameters): CreateResponse
    {
        $payload = Payload::create('v1/chat/completions', $parameters);

        $result = $this->transporter->requestObject($payload);

        return CreateResponse::from($result);
    }

    /**
     * Create a streaming chat completion request.
     *
     * @param array{
     *     model: string,
     *     messages: array<array{role: string, content: string|array}>,
     *     provider?: Provider|string,
     *     max_tokens?: int,
     *     temperature?: float,
     *     top_p?: float,
     *     frequency_penalty?: float,
     *     presence_penalty?: float,
     *     stop?: string|array<string>,
     *     tools?: array,
     *     tool_choice?: string|array,
     *     response_format?: array,
     *     seed?: int,
     *     logprobs?: bool,
     *     top_logprobs?: int
     * } $parameters
     */
    public function createStream(array $parameters): CreateStreamResponse
    {
        $parameters['stream'] = true;
        $payload = Payload::create('v1/chat/completions', $parameters);

        $result = $this->transporter->requestStream($payload);

        return CreateStreamResponse::from($result);
    }
}