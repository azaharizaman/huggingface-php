<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseChoice;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseMessage;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponseUsage;
use PHPUnit\Framework\TestCase;

final class CreateResponseTest extends TestCase
{
    public function testFromArray(): void
    {
        $attributes = [
            'id' => 'chatcmpl-123',
            'object' => 'chat.completion',
            'created' => 1677652288,
            'model' => 'microsoft/DialoGPT-medium',
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => 'Hello! How can I help you today?',
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => [
                'prompt_tokens' => 9,
                'completion_tokens' => 12,
                'total_tokens' => 21,
            ],
        ];

        $response = CreateResponse::from($attributes);

        $this->assertSame('chatcmpl-123', $response->id);
        $this->assertSame('chat.completion', $response->object);
        $this->assertSame(1677652288, $response->created);
        $this->assertSame('microsoft/DialoGPT-medium', $response->model);
        $this->assertCount(1, $response->choices);
        
        $choice = $response->choices[0];
        $this->assertInstanceOf(CreateResponseChoice::class, $choice);
        $this->assertSame(0, $choice->index);
        $this->assertSame('stop', $choice->finishReason);
        
        $message = $choice->message;
        $this->assertInstanceOf(CreateResponseMessage::class, $message);
        $this->assertSame('assistant', $message->role);
        $this->assertSame('Hello! How can I help you today?', $message->content);
        
        $usage = $response->usage;
        $this->assertInstanceOf(CreateResponseUsage::class, $usage);
        $this->assertSame(9, $usage->promptTokens);
        $this->assertSame(12, $usage->completionTokens);
        $this->assertSame(21, $usage->totalTokens);
    }

    public function testToArray(): void
    {
        $attributes = [
            'id' => 'chatcmpl-123',
            'object' => 'chat.completion',
            'created' => 1677652288,
            'model' => 'microsoft/DialoGPT-medium',
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => 'Hello!',
                    ],
                    'finish_reason' => 'stop',
                ],
            ],
            'usage' => [
                'prompt_tokens' => 9,
                'completion_tokens' => 12,
                'total_tokens' => 21,
            ],
        ];

        $response = CreateResponse::from($attributes);
        $array = $response->toArray();

        $this->assertSame('chatcmpl-123', $array['id']);
        $this->assertSame('chat.completion', $array['object']);
        $this->assertSame(1677652288, $array['created']);
        $this->assertSame('microsoft/DialoGPT-medium', $array['model']);
        $this->assertIsArray($array['choices']);
        $this->assertIsArray($array['usage']);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            'id' => 'chatcmpl-123',
            'object' => 'chat.completion',
            'created' => 1677652288,
            'model' => 'microsoft/DialoGPT-medium',
            'choices' => [],
            'usage' => [
                'prompt_tokens' => 9,
                'completion_tokens' => 12,
                'total_tokens' => 21,
            ],
        ];

        $response = CreateResponse::from($attributes);

        $this->assertSame('chatcmpl-123', $response['id']);
        $this->assertSame('chat.completion', $response['object']);
        $this->assertSame(1677652288, $response['created']);
        $this->assertSame('microsoft/DialoGPT-medium', $response['model']);
        $this->assertTrue(isset($response['usage']));
        $this->assertFalse(isset($response['nonexistent']));
    }
}