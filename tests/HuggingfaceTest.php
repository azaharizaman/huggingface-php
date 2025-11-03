<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests;

use AzahariZaman\Huggingface\Client;
use AzahariZaman\Huggingface\Factory;
use AzahariZaman\Huggingface\Huggingface;
use PHPUnit\Framework\TestCase;

final class HuggingfaceTest extends TestCase
{
    public function testClientCreatesClientWithApiKey(): void
    {
        $client = Huggingface::client('test-api-key');
        
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testFactoryCreatesNewFactoryInstance(): void
    {
        $factory = Huggingface::factory();
        
        $this->assertInstanceOf(Factory::class, $factory);
    }
}
