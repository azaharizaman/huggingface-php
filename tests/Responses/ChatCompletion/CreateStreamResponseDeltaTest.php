<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\Responses\ChatCompletion;

use AzahariZaman\Huggingface\Responses\ChatCompletion\CreateStreamResponseDelta;
use PHPUnit\Framework\TestCase;

final class CreateStreamResponseDeltaTest extends TestCase
{
    public function testFromArrayWithRoleAndContent(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'Hello, how can I help you?',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertSame('assistant', $delta->role);
        $this->assertSame('Hello, how can I help you?', $delta->content);
    }

    public function testFromArrayWithContentOnly(): void
    {
        $attributes = [
            'content' => 'Continuing the message...',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertNull($delta->role);
        $this->assertSame('Continuing the message...', $delta->content);
    }

    public function testFromArrayWithRoleOnly(): void
    {
        $attributes = [
            'role' => 'user',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertSame('user', $delta->role);
        $this->assertNull($delta->content);
    }

    public function testFromArrayEmpty(): void
    {
        $attributes = [];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertNull($delta->role);
        $this->assertNull($delta->content);
    }

    public function testToArrayWithBothFields(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'Test message',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);
        $array = $delta->toArray();

        $this->assertIsArray($array);
        $this->assertSame('assistant', $array['role']);
        $this->assertSame('Test message', $array['content']);
        $this->assertCount(2, $array);
    }

    public function testToArrayWithContentOnly(): void
    {
        $attributes = [
            'content' => 'Only content here',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);
        $array = $delta->toArray();

        $this->assertIsArray($array);
        $this->assertArrayNotHasKey('role', $array);
        $this->assertSame('Only content here', $array['content']);
        $this->assertCount(1, $array);
    }

    public function testToArrayWithRoleOnly(): void
    {
        $attributes = [
            'role' => 'system',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);
        $array = $delta->toArray();

        $this->assertIsArray($array);
        $this->assertSame('system', $array['role']);
        $this->assertArrayNotHasKey('content', $array);
        $this->assertCount(1, $array);
    }

    public function testToArrayEmpty(): void
    {
        $attributes = [];

        $delta = CreateStreamResponseDelta::from($attributes);
        $array = $delta->toArray();

        $this->assertIsArray($array);
        $this->assertEmpty($array);
        $this->assertArrayNotHasKey('role', $array);
        $this->assertArrayNotHasKey('content', $array);
    }

    public function testArrayAccessibility(): void
    {
        $attributes = [
            'role' => 'assistant',
            'content' => 'Accessible content',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertSame('assistant', $delta['role']);
        $this->assertSame('Accessible content', $delta['content']);
        $this->assertTrue(isset($delta['role']));
        $this->assertTrue(isset($delta['content']));
        $this->assertFalse(isset($delta['nonexistent']));
    }

    public function testArrayAccessibilityWithNullValues(): void
    {
        $attributes = [];

        $delta = CreateStreamResponseDelta::from($attributes);

        // When properties are null, they are not included in toArray()
        // so accessing them via array access should use isset() first
        $this->assertFalse(isset($delta['role']));
        $this->assertFalse(isset($delta['content']));

        // We can still access the properties directly
        $this->assertNull($delta->role);
        $this->assertNull($delta->content);
    }

    public function testProperties(): void
    {
        $attributes = [
            'role' => 'user',
            'content' => 'User message',
        ];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertIsString($delta->role);
        $this->assertIsString($delta->content);
        $this->assertSame('user', $delta->role);
        $this->assertSame('User message', $delta->content);
    }

    public function testPropertiesWithNulls(): void
    {
        $attributes = [];

        $delta = CreateStreamResponseDelta::from($attributes);

        $this->assertNull($delta->role);
        $this->assertNull($delta->content);
    }
}
