<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\Inference;

use AzahariZaman\Huggingface\Responses\Inference\CreateResponseSummarization;
use PHPUnit\Framework\TestCase;

final class CreateResponseSummarizationTest extends TestCase
{
    public function testFromCreatesInstance(): void
    {
        $attributes = [
            ['summary_text' => 'Summary content'],
        ];
        
        $response = CreateResponseSummarization::from($attributes);
        
        $this->assertInstanceOf(CreateResponseSummarization::class, $response);
    }

    public function testToArrayReturnsCorrectStructure(): void
    {
        $attributes = [
            ['summary_text' => 'Test summary'],
        ];
        
        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();
        
        $this->assertSame(['summary_text' => 'Test summary'], $array);
    }

    public function testArrayAccess(): void
    {
        $attributes = [
            ['summary_text' => 'Test summary'],
        ];
        $response = CreateResponseSummarization::from($attributes);
        
        $this->assertTrue(isset($response['summary_text']));
        $this->assertSame('Test summary', $response['summary_text']);
    }
}
