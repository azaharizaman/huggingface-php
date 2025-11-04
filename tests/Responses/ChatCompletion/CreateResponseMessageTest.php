<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseMessage;
use PHPUnit\Framework\TestCase;

final class CreateResponseMessageTest extends TestCase
{
    public function testFromArrayWithBasicMessage(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'Hello! How can I help you today?',
        ];

        $message = CreateResponseMessage::from($attributes);

        $this->assertSame('assistant', $message->role);
        $this->assertSame('Hello! How can I help you today?', $message->content);
        $this->assertNull($message->toolCalls);
    }

    public function testFromArrayWithToolCalls(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'I need to call a function.',
            'tool_calls' => [
                [
                    'id' => 'call_1',
                    'type' => 'function',
                    'function' => [
                        'name' => 'get_weather',
                        'arguments' => '{"location": "New York"}',
                    ],
                ],
            ],
        ];

        $message = CreateResponseMessage::from($attributes);

        $this->assertSame('assistant', $message->role);
        $this->assertSame('I need to call a function.', $message->content);
        $this->assertIsArray($message->toolCalls);
        $this->assertCount(1, $message->toolCalls);
        $this->assertSame('call_1', $message->toolCalls[0]['id']);
    }

    public function testToArrayWithBasicMessage(): void
    {
        $attributes = [
            'role' => 'user',
            'content' => 'What is the weather like?',
        ];

        $message = CreateResponseMessage::from($attributes);
        $array = $message->toArray();

        $this->assertIsArray($array);
        $this->assertSame('user', $array['role']);
        $this->assertSame('What is the weather like?', $array['content']);
        $this->assertArrayNotHasKey('tool_calls', $array);
    }

    public function testToArrayWithToolCalls(): void
    {
        $toolCalls = [
            [
                'id' => 'call_1',
                'type' => 'function',
                'function' => [
                    'name' => 'search_web',
                    'arguments' => '{"query": "PHP tutorials"}',
                ],
            ],
        ];

        $attributes = [
            'role' => 'assistant',
            'content' => 'Let me search for that.',
            'tool_calls' => $toolCalls,
        ];

        $message = CreateResponseMessage::from($attributes);
        $array = $message->toArray();

        $this->assertIsArray($array);
        $this->assertSame('assistant', $array['role']);
        $this->assertSame('Let me search for that.', $array['content']);
        $this->assertArrayHasKey('tool_calls', $array);
        $this->assertSame($toolCalls, $array['tool_calls']);
    }

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'Hello there!',
        ];

        $message = CreateResponseMessage::from($attributes);

        $this->assertSame('assistant', $message['role']);
        $this->assertSame('Hello there!', $message['content']);
        $this->assertTrue(isset($message['role']));
        $this->assertTrue(isset($message['content']));
        $this->assertFalse(isset($message['nonexistent']));
    }

    public function testProperties(): void
    {
        $toolCalls = [
            [
                'id' => 'call_123',
                'type' => 'function',
                'function' => [
                    'name' => 'calculate',
                    'arguments' => '{"a": 5, "b": 3}',
                ],
            ],
        ];

        $attributes = [
            'role' => 'assistant',
            'content' => 'I will calculate that for you.',
            'tool_calls' => $toolCalls,
        ];

        $message = CreateResponseMessage::from($attributes);

        $this->assertIsString($message->role);
        $this->assertIsString($message->content);
        $this->assertIsArray($message->toolCalls);
        $this->assertSame('assistant', $message->role);
        $this->assertSame('I will calculate that for you.', $message->content);
        $this->assertSame($toolCalls, $message->toolCalls);
    }
}