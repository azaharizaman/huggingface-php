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

    public function testGetIteratorWithNoStream(): void
    {
        // Create a response using a direct instantiation instead of trying to modify the readonly property
        $reflection = new \ReflectionClass(CreateStreamResponse::class);
        $instance = $reflection->newInstanceWithoutConstructor();

        // Set all properties using reflection
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($instance, 'chatcmpl-test');

        $objectProperty = $reflection->getProperty('object');
        $objectProperty->setAccessible(true);
        $objectProperty->setValue($instance, 'chat.completion.chunk');

        $createdProperty = $reflection->getProperty('created');
        $createdProperty->setAccessible(true);
        $createdProperty->setValue($instance, time());

        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);
        $modelProperty->setValue($instance, 'streaming');

        $choicesProperty = $reflection->getProperty('choices');
        $choicesProperty->setAccessible(true);
        $choicesProperty->setValue($instance, []);

        $systemFingerprintProperty = $reflection->getProperty('systemFingerprint');
        $systemFingerprintProperty->setAccessible(true);
        $systemFingerprintProperty->setValue($instance, null);

        $streamProperty = $reflection->getProperty('stream');
        $streamProperty->setAccessible(true);
        $streamProperty->setValue($instance, null);

        $iterator = $instance->getIterator();
        $this->assertInstanceOf(\Generator::class, $iterator);

        $chunks = iterator_to_array($iterator);
        $this->assertEmpty($chunks);
    }

    public function testGetIteratorWithValidStream(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\" World\"},\"finish_reason\":\"stop\"}]}\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertCount(2, $chunks);
        $this->assertSame('Hello', $chunks[0]['choices'][0]['delta']['content']);
        $this->assertSame(' World', $chunks[1]['choices'][0]['delta']['content']);
    }

    public function testGetIteratorWithMalformedJson(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"invalid\":json}\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Valid\"},\"finish_reason\":null}]}\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertCount(1, $chunks); // Only valid JSON should be yielded
        $this->assertSame('Valid', $chunks[0]['choices'][0]['delta']['content']);
    }

    public function testGetIteratorWithEmptyLines(): void
    {
        $streamData = "\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\n\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertCount(1, $chunks);
        $this->assertSame('Hello', $chunks[0]['choices'][0]['delta']['content']);
    }

    public function testGetIteratorWithNonDataLines(): void
    {
        $streamData = "event: ping\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\nevent: close\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertCount(1, $chunks);
        $this->assertSame('Hello', $chunks[0]['choices'][0]['delta']['content']);
    }

    public function testGetIteratorWithBufferRemaining(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertCount(1, $chunks);
        $this->assertSame('Hello', $chunks[0]['choices'][0]['delta']['content']);
    }

    public function testGetIteratorWithBufferRemainingMalformedJson(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"invalid\":json}";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertEmpty($chunks); // Malformed JSON should be skipped
    }

    public function testGetIteratorWithBufferRemainingDone(): void
    {
        $streamData = "data: [DONE]";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $iterator = $streamResponse->getIterator();

        $chunks = iterator_to_array($iterator);
        $this->assertEmpty($chunks); // [DONE] should not be yielded
    }

    public function testCollectWithEmptyChunks(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturn(true);
        $stream->method('read')->willReturn('');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $collected = $streamResponse->collect();

        $this->assertInstanceOf(\AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse::class, $collected);
        $this->assertSame('', $collected->choices[0]->message->content);
        $this->assertSame('stop', $collected->choices[0]->finishReason);
    }

    public function testCollectWithChunks(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\" World\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":10,\"completion_tokens\":5,\"total_tokens\":15}}\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $collected = $streamResponse->collect();

        $this->assertInstanceOf(\AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse::class, $collected);
        $this->assertSame('Hello World', $collected->choices[0]->message->content);
        $this->assertSame('stop', $collected->choices[0]->finishReason);
        $this->assertSame('chatcmpl-123', $collected->id);
        $this->assertSame(15, $collected->usage->totalTokens);
    }

    public function testCollectWithoutUsageInLastChunk(): void
    {
        $streamData = "data: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\"Hello\"},\"finish_reason\":null}]}\n\ndata: {\"id\":\"chatcmpl-123\",\"object\":\"chat.completion.chunk\",\"created\":1677652288,\"model\":\"microsoft/DialoGPT-medium\",\"choices\":[{\"index\":0,\"delta\":{\"content\":\" World\"},\"finish_reason\":\"stop\"}]}\n\ndata: [DONE]\n\n";

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('eof')->willReturnOnConsecutiveCalls(false, false, true);
        $stream->method('read')->willReturnOnConsecutiveCalls($streamData, '');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);

        $streamResponse = CreateStreamResponse::from($response);
        $collected = $streamResponse->collect();

        $this->assertInstanceOf(\AzahariZaman\Huggingface\Responses\ChatCompletion\CreateResponse::class, $collected);
        $this->assertSame('Hello World', $collected->choices[0]->message->content);
        $this->assertSame(0, $collected->usage->totalTokens); // Default usage when not provided
    }
}
