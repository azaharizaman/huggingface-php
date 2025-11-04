<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Responses\ChatCompletion;

use Psr\Http\Message\ResponseInterface;
use AzahariZaman\Huggingface\Contracts\ResponseContract;
use AzahariZaman\Huggingface\Responses\Concerns\ArrayAccessible;

/**
 * @implements ResponseContract<array{id: string, object: string, created: int, model: string, choices: array<int, array{index: int, delta: array{role?: string, content?: string}, finish_reason: string|null}>}>
 */
final class CreateStreamResponse implements ResponseContract
{
    use ArrayAccessible;

    /**
     * @param array<int, CreateStreamResponseChoice> $choices
     */
    private function __construct(
        public readonly string $id,
        public readonly string $object,
        public readonly int $created,
        public readonly string $model,
        public readonly array $choices,
        public readonly ?string $systemFingerprint = null,
        private readonly ?ResponseInterface $stream = null,
    ) {
    }

    /**
     * Acts as static factory, and returns a new Response instance.
     *
     * @param  ResponseInterface $stream
     */
    public static function from(ResponseInterface $stream): self
    {
        // For streaming responses, we return a wrapper that can be iterated
        return new self(
            'chatcmpl-' . uniqid(),
            'chat.completion.chunk',
            time(),
            'streaming',
            [],
            null,
            $stream
        );
    }

    /**
     * Get the streaming response as an iterator
     *
     * @return \Generator<array>
     */
    public function getIterator(): \Generator
    {
        if ($this->stream === null) {
            return;
        }

        $body = $this->stream->getBody();

        $buffer = '';
        
        while (!$body->eof()) {
            $chunk = $body->read(8192); // Read in larger chunks for efficiency
            if ($chunk === '') {
                break;
            }
            
            $buffer .= $chunk;
            
            // Process complete lines from buffer
            while (($pos = strpos($buffer, "\n")) !== false) {
                $line = substr($buffer, 0, $pos);
                $buffer = substr($buffer, $pos + 1);
                
                $line = trim($line);

                // Skip empty lines and non-data lines
                if (empty($line) || !str_starts_with($line, 'data: ')) {
                    continue;
                }

                $data = substr($line, 6); // Remove "data: " prefix

                // Handle the end of stream
                if ($data === '[DONE]') {
                    break 2; // Break both loops
                }

                try {
                    $decoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
                    yield $decoded;
                } catch (\JsonException $e) {
                    // Skip malformed JSON
                    continue;
                }
            }
        }
        
        // Process any remaining data in buffer
        if (!empty($buffer)) {
            $line = trim($buffer);
            if (!empty($line) && str_starts_with($line, 'data: ')) {
                $data = substr($line, 6);
                if ($data !== '[DONE]') {
                    try {
                        $decoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
                        yield $decoded;
                    } catch (\JsonException $e) {
                        // Skip malformed JSON
                    }
                }
            }
        }
    }

    /**
     * Collect all streaming chunks into a single response
     */
    public function collect(): CreateResponse
    {
        $chunks = iterator_to_array($this->getIterator());

        if (empty($chunks)) {
            // Return a default response if no chunks
            return CreateResponse::from([
                'id' => $this->id,
                'object' => 'chat.completion',
                'created' => $this->created,
                'model' => $this->model,
                'choices' => [
                    [
                        'index' => 0,
                        'message' => [
                            'role' => 'assistant',
                            'content' => ''
                        ],
                        'finish_reason' => 'stop'
                    ]
                ],
                'usage' => [
                    'prompt_tokens' => 0,
                    'completion_tokens' => 0,
                    'total_tokens' => 0
                ]
            ]);
        }

        // Combine all chunks into a single response
        $lastChunk = end($chunks);
        $content = '';

        foreach ($chunks as $chunk) {
            if (isset($chunk['choices'][0]['delta']['content'])) {
                $content .= $chunk['choices'][0]['delta']['content'];
            }
        }

        return CreateResponse::from([
            'id' => $lastChunk['id'] ?? $this->id,
            'object' => 'chat.completion',
            'created' => $lastChunk['created'] ?? $this->created,
            'model' => $lastChunk['model'] ?? $this->model,
            'choices' => [
                [
                    'index' => 0,
                    'message' => [
                        'role' => 'assistant',
                        'content' => $content
                    ],
                    'finish_reason' => $lastChunk['choices'][0]['finish_reason'] ?? 'stop'
                ]
            ],
            'usage' => $lastChunk['usage'] ?? [
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
                'total_tokens' => 0
            ]
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'object' => $this->object,
            'created' => $this->created,
            'model' => $this->model,
            'choices' => array_map(
                fn (CreateStreamResponseChoice $choice): array => $choice->toArray(),
                $this->choices
            ),
            'system_fingerprint' => $this->systemFingerprint,
        ];
    }
}
