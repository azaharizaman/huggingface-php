<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Hub;

use AzahariZaman\Huggingface\Responses\Hub\ModelInfoResponse;
use PHPUnit\Framework\TestCase;

final class ModelInfoResponseTest extends TestCase
{
    public function testFromArray(): void
    {
        $attributes = [
            'id' => 'microsoft/DialoGPT-medium',
            '_id' => '6124b4d0e5c2d05bb8d5c123',
            'inference' => 'text-generation',
            'author' => 'microsoft',
            'last_modified' => '2021-10-15T15:30:00.000Z',
            'private' => false,
            'downloads' => 50000,
            'likes' => 1000,
            'tags' => ['pytorch', 'safetensors', 'conversational'],
            'pipeline_tag' => 'conversational',
            'library_name' => 'transformers',
            'sha' => 'abc123def456',
            'created_at' => '2021-01-01T00:00:00.000Z',
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame('microsoft/DialoGPT-medium', $response->id);
        $this->assertSame('6124b4d0e5c2d05bb8d5c123', $response->_id);
        $this->assertSame('text-generation', $response->inference);
        $this->assertSame('microsoft', $response->author);
        $this->assertSame('2021-10-15T15:30:00.000Z', $response->last_modified);
        $this->assertSame(50000, $response->downloads);
        $this->assertSame(1000, $response->likes);
        $this->assertSame(['pytorch', 'safetensors', 'conversational'], $response->tags);
        $this->assertSame('conversational', $response->pipeline_tag);
        $this->assertSame('transformers', $response->library_name);
        $this->assertSame('abc123def456', $response->sha);
        $this->assertSame('2021-01-01T00:00:00.000Z', $response->created_at);
    }

    public function testFromArrayWithMinimalData(): void
    {
        $attributes = [
            'id' => 'simple-model',
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame('simple-model', $response->id);
        $this->assertNull($response->_id);
        $this->assertNull($response->inference);
        $this->assertNull($response->author);
        $this->assertNull($response->downloads);
        $this->assertNull($response->likes);
        $this->assertNull($response->tags);
        $this->assertNull($response->pipeline_tag);
        $this->assertNull($response->library_name);
    }

    public function testToArray(): void
    {
        $attributes = [
            'id' => 'microsoft/DialoGPT-medium',
            'author' => 'microsoft',
            'downloads' => 50000,
            'likes' => 1000,
            'tags' => ['pytorch', 'transformers'],
            'pipeline_tag' => 'conversational',
        ];

        $response = ModelInfoResponse::from($attributes);
        $array = $response->toArray();

        $this->assertSame('microsoft/DialoGPT-medium', $array['id']);
        $this->assertSame('microsoft', $array['author']);
        $this->assertSame(50000, $array['downloads']);
        $this->assertSame(1000, $array['likes']);
        $this->assertSame(['pytorch', 'transformers'], $array['tags']);
        $this->assertSame('conversational', $array['pipeline_tag']);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            'id' => 'test-model',
            'author' => 'testuser',
            'downloads' => 100,
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame('test-model', $response['id']);
        $this->assertSame('testuser', $response['author']);
        $this->assertSame(100, $response['downloads']);
        $this->assertTrue(isset($response['id']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testFromArrayWithAllFields(): void
    {
        $attributes = [
            'id' => 'full-model',
            '_id' => 'internal123',
            'inference' => 'text-classification',
            'author' => 'fulluser',
            'last_modified' => '2023-12-01T10:00:00.000Z',
            'private' => true,
            'downloads' => 5000,
            'likes' => 500,
            'tags' => ['tag1', 'tag2', 'tag3'],
            'pipeline_tag' => 'text-classification',
            'library_name' => 'pytorch',
            'sha' => 'hash123',
            'created_at' => '2023-01-01T00:00:00.000Z',
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame('full-model', $response->id);
        $this->assertSame('internal123', $response->_id);
        $this->assertSame('text-classification', $response->inference);
        $this->assertSame('fulluser', $response->author);
        $this->assertSame('2023-12-01T10:00:00.000Z', $response->last_modified);
        $this->assertSame(5000, $response->downloads);
        $this->assertSame(500, $response->likes);
        $this->assertSame(['tag1', 'tag2', 'tag3'], $response->tags);
        $this->assertSame('text-classification', $response->pipeline_tag);
        $this->assertSame('pytorch', $response->library_name);
        $this->assertSame('hash123', $response->sha);
        $this->assertSame('2023-01-01T00:00:00.000Z', $response->created_at);
    }

    public function testFromArrayWithNoDownloadsOrLikes(): void
    {
        $attributes = [
            'id' => 'new-model',
            'author' => 'newuser',
            'downloads' => 0,
            'likes' => 0,
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame(0, $response->downloads);
        $this->assertSame(0, $response->likes);
    }

    public function testFromArrayWithEmptyTags(): void
    {
        $attributes = [
            'id' => 'model-no-tags',
            'tags' => [],
        ];

        $response = ModelInfoResponse::from($attributes);

        $this->assertSame([], $response->tags);
    }
}
