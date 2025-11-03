<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests;

use AzahariZaman\Huggingface\Client;
use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Resources\Inference;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testInferenceReturnsInferenceResource(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $client = new Client($transporter);
        
        $inference = $client->inference();
        
        $this->assertInstanceOf(Inference::class, $inference);
    }
}
