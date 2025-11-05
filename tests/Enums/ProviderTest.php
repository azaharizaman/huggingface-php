<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Enums;

use AzahariZaman\Huggingface\Enums\Provider;
use PHPUnit\Framework\TestCase;

final class ProviderTest extends TestCase
{
    public function testAutoHasCorrectValue(): void
    {
        $this->assertSame('auto', Provider::AUTO->value);
    }

    public function testHuggingfaceHasCorrectValue(): void
    {
        $this->assertSame('hf-inference', Provider::HUGGINGFACE->value);
    }

    public function testSambanovaHasCorrectValue(): void
    {
        $this->assertSame('sambanova', Provider::SAMBANOVA->value);
    }

    public function testTogetherHasCorrectValue(): void
    {
        $this->assertSame('together', Provider::TOGETHER->value);
    }

    public function testReplicateHasCorrectValue(): void
    {
        $this->assertSame('replicate', Provider::REPLICATE->value);
    }

    public function testFalAiHasCorrectValue(): void
    {
        $this->assertSame('fal-ai', Provider::FAL_AI->value);
    }

    public function testFireworksAiHasCorrectValue(): void
    {
        $this->assertSame('fireworks-ai', Provider::FIREWORKS_AI->value);
    }

    public function testCerebrasHasCorrectValue(): void
    {
        $this->assertSame('cerebras', Provider::CEREBRAS->value);
    }

    public function testCohereHasCorrectValue(): void
    {
        $this->assertSame('cohere', Provider::COHERE->value);
    }

    public function testNovitaHasCorrectValue(): void
    {
        $this->assertSame('novita', Provider::NOVITA->value);
    }

    public function testGroqHasCorrectValue(): void
    {
        $this->assertSame('groq', Provider::GROQ->value);
    }

    public function testMistralHasCorrectValue(): void
    {
        $this->assertSame('mistral', Provider::MISTRAL->value);
    }

    public function testOpenAiHasCorrectValue(): void
    {
        $this->assertSame('openai', Provider::OPENAI->value);
    }

    public function testAnthropicHasCorrectValue(): void
    {
        $this->assertSame('anthropic', Provider::ANTHROPIC->value);
    }

    public function testDeepseekHasCorrectValue(): void
    {
        $this->assertSame('deepseek', Provider::DEEPSEEK->value);
    }

    public function testNebiusHasCorrectValue(): void
    {
        $this->assertSame('nebius', Provider::NEBIUS->value);
    }

    public function testNvidiaHasCorrectValue(): void
    {
        $this->assertSame('nvidia', Provider::NVIDIA->value);
    }

    public function testCanGetAllProviders(): void
    {
        $providers = Provider::cases();
        $this->assertCount(17, $providers);

        $values = array_map(fn($case) => $case->value, $providers);
        $this->assertContains('auto', $values);
        $this->assertContains('hf-inference', $values);
        $this->assertContains('sambanova', $values);
        $this->assertContains('together', $values);
        $this->assertContains('replicate', $values);
    }
}
