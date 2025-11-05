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

    public function testFromWithLongSummary(): void
    {
        $longSummary = 'This is a comprehensive summary that spans multiple sentences and contains detailed information about the original text. It includes key points, main ideas, and important details that were extracted from the source material.';
        $attributes = [
            ['summary_text' => $longSummary],
        ];

        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();

        $this->assertSame($longSummary, $array['summary_text']);
    }

    public function testFromWithEmptySummary(): void
    {
        $attributes = [
            ['summary_text' => ''],
        ];

        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();

        $this->assertSame('', $array['summary_text']);
    }

    public function testFromWithMultilineSummary(): void
    {
        $multilineSummary = "First paragraph of summary.\n\nSecond paragraph with more details.\n\nFinal paragraph with conclusions.";
        $attributes = [
            ['summary_text' => $multilineSummary],
        ];

        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();

        $this->assertSame($multilineSummary, $array['summary_text']);
        $this->assertStringContainsString("\n", $array['summary_text']);
    }

    public function testFromWithSpecialCharacters(): void
    {
        $specialSummary = 'Summary with "quotes", bullet points: • • •, and dashes — em-dash & en-dash.';
        $attributes = [
            ['summary_text' => $specialSummary],
        ];

        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();

        $this->assertSame($specialSummary, $array['summary_text']);
    }

    public function testArrayOffsetExists(): void
    {
        $attributes = [
            ['summary_text' => 'Brief summary'],
        ];

        $response = CreateResponseSummarization::from($attributes);

        $this->assertTrue(isset($response['summary_text']));
        $this->assertFalse(isset($response['nonexistent']));
    }

    public function testToArrayStructure(): void
    {
        $attributes = [
            ['summary_text' => 'Document summary'],
        ];

        $response = CreateResponseSummarization::from($attributes);
        $array = $response->toArray();

        $this->assertArrayHasKey('summary_text', $array);
        $this->assertCount(1, $array);
    }
}
