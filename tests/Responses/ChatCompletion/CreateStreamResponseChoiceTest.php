<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponseChoice;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponseDelta;
use PHPUnit\Framework\TestCase;

final class CreateStreamResponseChoiceTest extends TestCase
{
    public function testFromArrayWithBasicChoice(): void
    {
        $attributes = [
            'index' => 0,
            'delta' => [
                'role' => 'assistant',
                'content' => 'Hello world',
            ],
            'finish_reason' => null,
        ];

        $choice = CreateStreamResponseChoice::from($attributes);

        $this->assertSame(0, $choice->index);
        $this->assertInstanceOf(CreateStreamResponseDelta::class, $choice->delta);
        $this->assertSame('assistant', $choice->delta->role);
        $this->assertSame('Hello world', $choice->delta->content);
        $this->assertNull($choice->finishReason);
    }

    public function testFromArrayWithFinishReason(): void
    {
        $attributes = [
            'index' => 1,
            'delta' => [
                'content' => 'Final message',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateStreamResponseChoice::from($attributes);

        $this->assertSame(1, $choice->index);
        $this->assertInstanceOf(CreateStreamResponseDelta::class, $choice->delta);
        $this->assertNull($choice->delta->role);
        $this->assertSame('Final message', $choice->delta->content);
        $this->assertSame('stop', $choice->finishReason);
    }

    public function testFromArrayWithoutFinishReason(): void
    {
        $attributes = [
            'index' => 0,
            'delta' => [
                'content' => 'Partial message',
            ],
        ];

        $choice = CreateStreamResponseChoice::from($attributes);

        $this->assertSame(0, $choice->index);
        $this->assertInstanceOf(CreateStreamResponseDelta::class, $choice->delta);
        $this->assertNull($choice->delta->role);
        $this->assertSame('Partial message', $choice->delta->content);
        $this->assertNull($choice->finishReason);
    }

    public function testToArray(): void
    {
        $attributes = [
            'index' => 2,
            'delta' => [
                'role' => 'assistant',
                'content' => 'Test content',
            ],
            'finish_reason' => 'length',
        ];

        $choice = CreateStreamResponseChoice::from($attributes);
        $array = $choice->toArray();

        $this->assertIsArray($array);
        $this->assertSame(2, $array['index']);
        $this->assertIsArray($array['delta']);
        $this->assertSame('assistant', $array['delta']['role']);
        $this->assertSame('Test content', $array['delta']['content']);
        $this->assertSame('length', $array['finish_reason']);
    }

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'index' => 0,
            'delta' => [
                'content' => 'Hello',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateStreamResponseChoice::from($attributes);

        $this->assertSame(0, $choice['index']);
        $this->assertIsArray($choice['delta']);
        $this->assertSame('stop', $choice['finish_reason']);
        $this->assertTrue(isset($choice['index']));
        $this->assertTrue(isset($choice['delta']));
        $this->assertTrue(isset($choice['finish_reason']));
        $this->assertFalse(isset($choice['nonexistent']));
    }

    public function testProperties(): void
    {
        $attributes = [
            'index' => 5,
            'delta' => [
                'role' => 'user',
                'content' => 'Question?',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateStreamResponseChoice::from($attributes);

        $this->assertIsInt($choice->index);
        $this->assertInstanceOf(CreateStreamResponseDelta::class, $choice->delta);
        $this->assertIsString($choice->finishReason);
        $this->assertSame(5, $choice->index);
        $this->assertSame('user', $choice->delta->role);
        $this->assertSame('Question?', $choice->delta->content);
        $this->assertSame('stop', $choice->finishReason);
    }
}
