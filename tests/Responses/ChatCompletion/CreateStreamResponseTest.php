<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class CreateStreamResponseTest extends TestCase
{
    public function testFromResponseInterface(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('__toString')->willReturn("data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\ndata: [DONE]\n\n");
        
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $streamResponse = CreateStreamResponse::from($response);
        
        $this->assertInstanceOf(CreateStreamResponse::class, $streamResponse);
        $this->assertSame('streaming', $streamResponse->model);
        $this->assertSame('chat.completion.chunk', $streamResponse->object);
        $this->assertIsString($streamResponse->id);
        $this->assertStringStartsWith('chatcmpl-', $streamResponse->id);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $streamResponse = CreateStreamResponse::from($response);
        $array = $streamResponse->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('object', $array);
        $this->assertArrayHasKey('created', $array);
        $this->assertArrayHasKey('model', $array);
        $this->assertArrayHasKey('choices', $array);
        
        $this->assertSame('streaming', $array['model']);
        $this->assertSame('chat.completion.chunk', $array['object']);
        $this->assertIsArray($array['choices']);
    }

    public function testArrayAccessibility(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $streamResponse = CreateStreamResponse::from($response);
        
        $this->assertSame('streaming', $streamResponse['model']);
        $this->assertSame('chat.completion.chunk', $streamResponse['object']);
        $this->assertTrue(isset($streamResponse['id']));
        $this->assertTrue(isset($streamResponse['created']));
        $this->assertFalse(isset($streamResponse['nonexistent']));
    }

    public function testProperties(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        
        $streamResponse = CreateStreamResponse::from($response);
        
        $this->assertIsString($streamResponse->id);
        $this->assertSame('chat.completion.chunk', $streamResponse->object);
        $this->assertIsInt($streamResponse->created);
        $this->assertSame('streaming', $streamResponse->model);
        $this->assertIsArray($streamResponse->choices);
        $this->assertNull($streamResponse->systemFingerprint);
    }
}