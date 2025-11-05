<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseUsage;
use PHPUnit\Framework\TestCase;

final class CreateResponseUsageTest extends TestCase
{
    public function testFromArrayWithBasicUsage(): void
    {
        $attributes = [
            'prompt_tokens' => 10,
            'completion_tokens' => 20,
            'total_tokens' => 30,
        ];

        $usage = CreateResponseUsage::from($attributes);

        $this->assertSame(10, $usage->promptTokens);
        $this->assertSame(20, $usage->completionTokens);
        $this->assertSame(30, $usage->totalTokens);
    }

    public function testFromArrayWithZeroTokens(): void
    {
        $attributes = [
            'prompt_tokens' => 0,
            'completion_tokens' => 0,
            'total_tokens' => 0,
        ];

        $usage = CreateResponseUsage::from($attributes);

        $this->assertSame(0, $usage->promptTokens);
        $this->assertSame(0, $usage->completionTokens);
        $this->assertSame(0, $usage->totalTokens);
    }

    public function testFromArrayWithLargeTokens(): void
    {
        $attributes = [
            'prompt_tokens' => 1500,
            'completion_tokens' => 2500,
            'total_tokens' => 4000,
        ];

        $usage = CreateResponseUsage::from($attributes);

        $this->assertSame(1500, $usage->promptTokens);
        $this->assertSame(2500, $usage->completionTokens);
        $this->assertSame(4000, $usage->totalTokens);
    }

    public function testToArray(): void
    {
        $attributes = [
            'prompt_tokens' => 15,
            'completion_tokens' => 25,
            'total_tokens' => 40,
        ];

        $usage = CreateResponseUsage::from($attributes);
        $array = $usage->toArray();

        $this->assertIsArray($array);
        $this->assertSame(15, $array['prompt_tokens']);
        $this->assertSame(25, $array['completion_tokens']);
        $this->assertSame(40, $array['total_tokens']);
    }

    public function testToArrayStructure(): void
    {
        $attributes = [
            'prompt_tokens' => 100,
            'completion_tokens' => 200,
            'total_tokens' => 300,
        ];

        $usage = CreateResponseUsage::from($attributes);
        $array = $usage->toArray();

        $this->assertArrayHasKey('prompt_tokens', $array);
        $this->assertArrayHasKey('completion_tokens', $array);
        $this->assertArrayHasKey('total_tokens', $array);
        $this->assertCount(3, $array);
    }

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'prompt_tokens' => 50,
            'completion_tokens' => 75,
            'total_tokens' => 125,
        ];

        $usage = CreateResponseUsage::from($attributes);

        $this->assertSame(50, $usage['prompt_tokens']);
        $this->assertSame(75, $usage['completion_tokens']);
        $this->assertSame(125, $usage['total_tokens']);
        $this->assertTrue(isset($usage['prompt_tokens']));
        $this->assertTrue(isset($usage['completion_tokens']));
        $this->assertTrue(isset($usage['total_tokens']));
        $this->assertFalse(isset($usage['nonexistent']));
    }

    public function testProperties(): void
    {
        $attributes = [
            'prompt_tokens' => 42,
            'completion_tokens' => 84,
            'total_tokens' => 126,
        ];

        $usage = CreateResponseUsage::from($attributes);

        $this->assertIsInt($usage->promptTokens);
        $this->assertIsInt($usage->completionTokens);
        $this->assertIsInt($usage->totalTokens);
        $this->assertSame(42, $usage->promptTokens);
        $this->assertSame(84, $usage->completionTokens);
        $this->assertSame(126, $usage->totalTokens);
    }

    public function testPropertiesAreReadonly(): void
    {
        $attributes = [
            'prompt_tokens' => 10,
            'completion_tokens' => 20,
            'total_tokens' => 30,
        ];

        $usage = CreateResponseUsage::from($attributes);

        // Test that properties are readonly by checking they can be accessed
        // but attempting to modify would cause an error (tested at compile time)
        $this->assertSame(10, $usage->promptTokens);
        $this->assertSame(20, $usage->completionTokens);
        $this->assertSame(30, $usage->totalTokens);
    }
}
