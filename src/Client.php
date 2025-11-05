<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Resources\Inference;
use AzahariZaman\Huggingface\Resources\ChatCompletion;
use AzahariZaman\Huggingface\Resources\Hub;
use AzahariZaman\Huggingface\Transporters\HttpTransporter;
use AzahariZaman\Huggingface\ValueObjects\Transporter\BaseUri;

final class Client implements Contracts\ClientContract
{
    private ?TransporterContract $chatTransporter = null;
    private ?TransporterContract $hubTransporter = null;

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

    /**
     * Chat Completion
     */
    public function chatCompletion(): ChatCompletion
    {
        if ($this->chatTransporter === null) {
            $this->chatTransporter = $this->createChatTransporter();
        }

        return new ChatCompletion($this->chatTransporter);
    }

    /**
     * Hub API
     */
    public function hub(): Hub
    {
        if ($this->hubTransporter === null) {
            $this->hubTransporter = $this->createHubTransporter();
        }

        return new Hub($this->hubTransporter);
    }

    /**
     * Creates a transporter configured for chat completions
     */
    private function createChatTransporter(): TransporterContract
    {
        if ($this->transporter instanceof HttpTransporter) {
            // Create new transporter with chat completion base URI
            $chatBaseUri = BaseUri::from('router.huggingface.co');

            return $this->transporter->withBaseUri($chatBaseUri);
        }

        return $this->transporter;
    }

    /**
     * Creates a transporter configured for Hub API
     */
    private function createHubTransporter(): TransporterContract
    {
        if ($this->transporter instanceof HttpTransporter) {
            // Create new transporter with Hub API base URI
            $hubBaseUri = BaseUri::from('huggingface.co');

            return $this->transporter->withBaseUri($hubBaseUri);
        }

        return $this->transporter;
    }
}
