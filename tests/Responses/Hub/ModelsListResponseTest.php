<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Hub;

use AzahariZaman\Huggingface\Responses\Hub\ModelInfoResponse;
use AzahariZaman\Huggingface\Responses\Hub\ModelsListResponse;
use PHPUnit\Framework\TestCase;

final class ModelsListResponseTest extends TestCase
{
    public function testFromArray(): void
    {
        $attributes = [
            [
                'id' => 'microsoft/DialoGPT-medium',
                'author' => 'microsoft',
                'downloads' => 50000,
                'likes' => 1000,
                'tags' => ['pytorch', 'conversational'],
                'pipeline_tag' => 'conversational',
                'library_name' => 'transformers',
            ],
            [
                'id' => 'gpt2',
                'author' => 'openai-community',
                'downloads' => 100000,
                'likes' => 2000,
                'tags' => ['pytorch', 'tf'],
                'pipeline_tag' => 'text-generation',
                'library_name' => 'transformers',
            ],
        ];

        $response = ModelsListResponse::from($attributes);

        $this->assertInstanceOf(ModelsListResponse::class, $response);
        $this->assertCount(2, $response->models);

        $firstModel = $response->models[0];
        $this->assertInstanceOf(ModelInfoResponse::class, $firstModel);
        $this->assertSame('microsoft/DialoGPT-medium', $firstModel->id);
        $this->assertSame('microsoft', $firstModel->author);
        $this->assertSame(50000, $firstModel->downloads);
        $this->assertSame(1000, $firstModel->likes);

        $secondModel = $response->models[1];
        $this->assertInstanceOf(ModelInfoResponse::class, $secondModel);
        $this->assertSame('gpt2', $secondModel->id);
        $this->assertSame('openai-community', $secondModel->author);
        $this->assertSame(100000, $secondModel->downloads);
        $this->assertSame(2000, $secondModel->likes);
    }

    public function testFromEmptyArray(): void
    {
        $attributes = [];

        $response = ModelsListResponse::from($attributes);

        $this->assertInstanceOf(ModelsListResponse::class, $response);
        $this->assertCount(0, $response->models);
        $this->assertIsArray($response->models);
    }

    public function testToArray(): void
    {
        $attributes = [
            [
                'id' => 'test-model',
                'author' => 'testuser',
                'downloads' => 100,
            ],
        ];

        $response = ModelsListResponse::from($attributes);
        $array = $response->toArray();

        $this->assertIsArray($array);
        $this->assertCount(1, $array);

        $modelArray = $array[0];
        $this->assertSame('test-model', $modelArray['id']);
        $this->assertSame('testuser', $modelArray['author']);
        $this->assertSame(100, $modelArray['downloads']);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            [
                'id' => 'test-model-1',
                'author' => 'testuser1',
            ],
            [
                'id' => 'test-model-2',
                'author' => 'testuser2',
            ],
        ];

        $response = ModelsListResponse::from($attributes);

        $this->assertTrue(isset($response[0]));
        $this->assertTrue(isset($response[1]));
        $this->assertFalse(isset($response[2]));

        $firstModel = $response[0];
        $this->assertIsArray($firstModel);
        $this->assertSame('test-model-1', $firstModel['id']);
        $this->assertSame('testuser1', $firstModel['author']);
    }

    public function testFromSingleModel(): void
    {
        $attributes = [
            [
                'id' => 'single-model',
                'author' => 'singleauthor',
                'downloads' => 1000,
                'likes' => 50,
            ],
        ];

        $response = ModelsListResponse::from($attributes);

        $this->assertCount(1, $response->models);
        $this->assertInstanceOf(ModelInfoResponse::class, $response->models[0]);
        $this->assertSame('single-model', $response->models[0]->id);
    }

    public function testFromMultipleModelsWithDifferentData(): void
    {
        $attributes = [
            [
                'id' => 'model-1',
                'downloads' => 1000,
            ],
            [
                'id' => 'model-2',
                'likes' => 500,
            ],
            [
                'id' => 'model-3',
                'author' => 'author3',
            ],
        ];

        $response = ModelsListResponse::from($attributes);

        $this->assertCount(3, $response->models);
        $this->assertSame('model-1', $response->models[0]->id);
        $this->assertSame('model-2', $response->models[1]->id);
        $this->assertSame('model-3', $response->models[2]->id);
    }

    public function testToArrayWithMultipleModels(): void
    {
        $attributes = [
            [
                'id' => 'model-a',
                'author' => 'author-a',
                'downloads' => 100,
            ],
            [
                'id' => 'model-b',
                'author' => 'author-b',
                'downloads' => 200,
            ],
        ];

        $response = ModelsListResponse::from($attributes);
        $array = $response->toArray();

        $this->assertCount(2, $array);
        $this->assertSame('model-a', $array[0]['id']);
        $this->assertSame('author-a', $array[0]['author']);
        $this->assertSame('model-b', $array[1]['id']);
        $this->assertSame('author-b', $array[1]['author']);
    }
}
