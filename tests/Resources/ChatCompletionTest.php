<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Provider;
use AzahariZaman\Huggingface\Resources\ChatCompletion;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse;
use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class ChatCompletionTest extends TestCase
{
    public function testCreateReturnsCreateResponse(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
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
            ]);

        $chatCompletion = new ChatCompletion($transporter);

        $response = $chatCompletion->create([
            'model' => 'microsoft/DialoGPT-medium',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!']
            ],
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
        $this->assertSame('chatcmpl-123', $response->id);
        $this->assertSame('chat.completion', $response->object);
        $this->assertSame(1677652288, $response->created);
        $this->assertSame('microsoft/DialoGPT-medium', $response->model);
    }

    public function testCreateWithProvider(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                'id' => 'chatcmpl-123',
                'object' => 'chat.completion',
                'created' => 1677652288,
                'model' => 'microsoft/DialoGPT-medium',
                'choices' => [],
                'usage' => ['prompt_tokens' => 9, 'completion_tokens' => 12, 'total_tokens' => 21],
            ]);

        $chatCompletion = new ChatCompletion($transporter);

        $response = $chatCompletion->create([
            'model' => 'microsoft/DialoGPT-medium',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!']
            ],
            'provider' => Provider::SAMBANOVA,
        ]);

        $this->assertInstanceOf(CreateResponse::class, $response);
    }

    public function testCreateStreamReturnsCreateStreamResponse(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\ndata: [DONE]\n\n";
        
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($streamData);
        
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestStream')
            ->willReturn($response);

        $chatCompletion = new ChatCompletion($transporter);

        $result = $chatCompletion->createStream([
            'model' => 'microsoft/DialoGPT-medium',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!']
            ],
            'stream' => true,
        ]);

        $this->assertInstanceOf(CreateStreamResponse::class, $result);
    }

    public function testCreateStreamWithAllParameters(): void
    {
        $streamData = "data: {\"choices\":[{\"delta\":{\"content\":\"test\"}}]}\n\n";
        
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn($streamData);
        
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestStream')
            ->willReturn($response);

        $chatCompletion = new ChatCompletion($transporter);

        $result = $chatCompletion->createStream([
            'model' => 'microsoft/DialoGPT-medium',
            'messages' => [
                ['role' => 'user', 'content' => 'Hello!']
            ],
            'max_tokens' => 100,
            'temperature' => 0.7,
            'top_p' => 0.9,
            'frequency_penalty' => 0.1,
            'presence_penalty' => 0.1,
            'stream' => true,
            'provider' => Provider::TOGETHER,
        ]);

        $this->assertInstanceOf(CreateStreamResponse::class, $result);
    }
}