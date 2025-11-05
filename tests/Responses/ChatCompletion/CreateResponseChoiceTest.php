<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseChoice;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseMessage;
use PHPUnit\Framework\TestCase;

final class CreateResponseChoiceTest extends TestCase
{
    public function testFromArrayWithBasicChoice(): void
    {
        $attributes = [
            'index' => 0,
            'message' => [
                'role' => 'assistant',
                'content' => 'Hello! How can I help you today?',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertSame(0, $choice->index);
        $this->assertInstanceOf(CreateResponseMessage::class, $choice->message);
        $this->assertSame('assistant', $choice->message->role);
        $this->assertSame('Hello! How can I help you today?', $choice->message->content);
        $this->assertSame('stop', $choice->finishReason);
    }

    public function testFromArrayWithoutFinishReason(): void
    {
        $attributes = [
            'index' => 1,
            'message' => [
                'role' => 'user',
                'content' => 'What is the weather like?',
            ],
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertSame(1, $choice->index);
        $this->assertInstanceOf(CreateResponseMessage::class, $choice->message);
        $this->assertNull($choice->finishReason);
    }

    public function testFromArrayWithNullFinishReason(): void
    {
        $attributes = [
            'index' => 2,
            'message' => [
                'role' => 'assistant',
                'content' => 'Processing...',
            ],
            'finish_reason' => null,
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertSame(2, $choice->index);
        $this->assertNull($choice->finishReason);
    }

    public function testToArrayWithFinishReason(): void
    {
        $attributes = [
            'index' => 0,
            'message' => [
                'role' => 'assistant',
                'content' => 'The answer is 42.',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateResponseChoice::from($attributes);
        $array = $choice->toArray();

        $this->assertIsArray($array);
        $this->assertSame(0, $array['index']);
        $this->assertIsArray($array['message']);
        $this->assertSame('assistant', $array['message']['role']);
        $this->assertSame('The answer is 42.', $array['message']['content']);
        $this->assertSame('stop', $array['finish_reason']);
    }

    public function testToArrayWithoutFinishReason(): void
    {
        $attributes = [
            'index' => 1,
            'message' => [
                'role' => 'user',
                'content' => 'Hello',
            ],
        ];

        $choice = CreateResponseChoice::from($attributes);
        $array = $choice->toArray();

        $this->assertIsArray($array);
        $this->assertSame(1, $array['index']);
        $this->assertIsArray($array['message']);
        $this->assertNull($array['finish_reason']);
    }

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'index' => 0,
            'message' => [
                'role' => 'assistant',
                'content' => 'Test message',
            ],
            'finish_reason' => 'length',
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertSame(0, $choice['index']);
        $this->assertIsArray($choice['message']);
        $this->assertSame('length', $choice['finish_reason']);
        $this->assertTrue(isset($choice['index']));
        $this->assertTrue(isset($choice['message']));
        $this->assertTrue(isset($choice['finish_reason']));
        $this->assertFalse(isset($choice['nonexistent']));
    }

    public function testProperties(): void
    {
        $attributes = [
            'index' => 3,
            'message' => [
                'role' => 'assistant',
                'content' => 'This is a test',
            ],
            'finish_reason' => 'stop',
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertIsInt($choice->index);
        $this->assertInstanceOf(CreateResponseMessage::class, $choice->message);
        $this->assertIsString($choice->finishReason);
        $this->assertSame(3, $choice->index);
        $this->assertSame('stop', $choice->finishReason);
    }

    public function testFromArrayWithToolCalls(): void
    {
        $attributes = [
            'index' => 0,
            'message' => [
                'role' => 'assistant',
                'content' => 'I will search for that.',
                'tool_calls' => [
                    [
                        'id' => 'call_1',
                        'type' => 'function',
                        'function' => [
                            'name' => 'search',
                            'arguments' => '{"query": "test"}',
                        ],
                    ],
                ],
            ],
            'finish_reason' => 'tool_calls',
        ];

        $choice = CreateResponseChoice::from($attributes);

        $this->assertSame(0, $choice->index);
        $this->assertInstanceOf(CreateResponseMessage::class, $choice->message);
        $this->assertIsArray($choice->message->toolCalls);
        $this->assertCount(1, $choice->message->toolCalls);
        $this->assertSame('tool_calls', $choice->finishReason);
    }
}
