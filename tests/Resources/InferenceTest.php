<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Resources;

use AzahariZaman\Huggingface\Contracts\TransporterContract;
use AzahariZaman\Huggingface\Enums\Type;
use AzahariZaman\Huggingface\Resources\Inference;
use AzahariZaman\Huggingface\Responses\Inference\CreateResponse;
use PHPUnit\Framework\TestCase;

final class InferenceTest extends TestCase
{
    public function testCreateReturnsResponse(): void
    {
        $transporter = $this->createMock(TransporterContract::class);
        $transporter
            ->expects($this->once())
            ->method('requestObject')
            ->willReturn([
                ['generated_text' => 'Test generated text'],
            ]);
        
        $inference = new Inference($transporter);
        
        $response = $inference->create([
            'model' => 'gpt2',
            'type' => Type::TEXT_GENERATION,
            'inputs' => 'Test input',
        ]);
        
        $this->assertInstanceOf(CreateResponse::class, $response);
    }
}
