<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Resources\Hub;
use AzahariZaman\Huggingface\Responses\Hub\ModelInfoResponse;
use AzahariZaman\Huggingface\Responses\Hub\ModelsListResponse;
use PHPUnit\Framework\TestCase;

final class HubTest extends TestCase
{
    public function testGetModelReturnsModelInfoResponse(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
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
            ]);

        $hub = new Hub($transporter);

        $response = $hub->getModel(['model_id' => 'microsoft/DialoGPT-medium']);

        $this->assertInstanceOf(ModelInfoResponse::class, $response);
        $this->assertSame('microsoft/DialoGPT-medium', $response->id);
        $this->assertSame('microsoft', $response->author);
        $this->assertSame('2021-10-15T15:30:00.000Z', $response->last_modified);
        $this->assertSame(50000, $response->downloads);
        $this->assertSame(1000, $response->likes);
        $this->assertSame(['pytorch', 'safetensors', 'conversational'], $response->tags);
        $this->assertSame('conversational', $response->pipeline_tag);
        $this->assertSame('transformers', $response->library_name);
    }

    public function testListModelsReturnsModelsListResponse(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                [
                    'id' => 'microsoft/DialoGPT-medium',
                    'author' => 'microsoft',
                    'last_modified' => '2021-10-15T15:30:00.000Z',
                    'private' => false,
                    'downloads' => 50000,
                    'likes' => 1000,
                    'tags' => ['pytorch', 'safetensors', 'conversational'],
                    'pipeline_tag' => 'conversational',
                    'library_name' => 'transformers',
                ],
                [
                    'id' => 'gpt2',
                    'author' => 'openai-community',
                    'last_modified' => '2021-10-15T15:30:00.000Z',
                    'private' => false,
                    'downloads' => 100000,
                    'likes' => 2000,
                    'tags' => ['pytorch', 'tf', 'jax', 'tflite'],
                    'pipeline_tag' => 'text-generation',
                    'library_name' => 'transformers',
                ],
            ]);

        $hub = new Hub($transporter);

        $response = $hub->listModels(['pipeline_tag' => 'text-generation']);

        $this->assertInstanceOf(ModelsListResponse::class, $response);
        $this->assertCount(2, $response->models);

        $firstModel = $response->models[0];
        $this->assertSame('microsoft/DialoGPT-medium', $firstModel->id);
        $this->assertSame('microsoft', $firstModel->author);

        $secondModel = $response->models[1];
        $this->assertSame('gpt2', $secondModel->id);
        $this->assertSame('openai-community', $secondModel->author);
    }

    public function testListModelsWithNoParameters(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([]);

        $hub = new Hub($transporter);

        $response = $hub->listModels();

        $this->assertInstanceOf(ModelsListResponse::class, $response);
        $this->assertIsArray($response->models);
    }

    public function testWhoamiReturnsArray(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                'type' => 'user',
                'name' => 'testuser',
                'fullname' => 'Test User',
                'email' => 'test@example.com',
                'emailVerified' => true,
                'plan' => 'FREE',
                'periodEnd' => null,
                'avatarUrl' => 'https://example.com/avatar.jpg',
                'orgs' => [
                    [
                        'name' => 'testorg',
                        'fullname' => 'Test Organization',
                        'roleInOrg' => 'admin',
                    ],
                ],
            ]);

        $hub = new Hub($transporter);

        $response = $hub->whoami();

        $this->assertIsArray($response);
        $this->assertSame('user', $response['type']);
        $this->assertSame('testuser', $response['name']);
        $this->assertSame('Test User', $response['fullname']);
        $this->assertSame('test@example.com', $response['email']);
        $this->assertTrue($response['emailVerified']);
        $this->assertSame('FREE', $response['plan']);
        $this->assertNull($response['periodEnd']);
        $this->assertSame('https://example.com/avatar.jpg', $response['avatarUrl']);
        $this->assertCount(1, $response['orgs']);

        $org = $response['orgs'][0];
        $this->assertSame('testorg', $org['name']);
        $this->assertSame('Test Organization', $org['fullname']);
        $this->assertSame('admin', $org['roleInOrg']);
    }
}
