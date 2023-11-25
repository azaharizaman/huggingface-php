<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Contracts;

use AzahariZaman\Huggingface\Exceptions\ErrorException;
use AzahariZaman\Huggingface\Exceptions\TransporterException;
use AzahariZaman\Huggingface\Exceptions\UnserializableResponse;
use AzahariZaman\Huggingface\ValueObjects\Transporter\Payload;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
interface TransporterContract
{
    /**
     * Sends a request to a server.
     **
     * @return array<array-key, mixed>
     *
     * @throws ErrorException|UnserializableResponse|TransporterException
     */
    public function requestObject(Payload $payload): array|string;

    /**
     * Sends a content request to a server.
     *
     * @throws ErrorException|TransporterException
     */
    public function requestContent(Payload $payload): string;

    /**
     * Sends a stream request to a server.
     **
     * @throws ErrorException
     */
    public function requestStream(Payload $payload): ResponseInterface;
}
