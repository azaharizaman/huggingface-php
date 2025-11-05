<?php

declare(strict_types=1);

namespace AzahariZaman\Huggingface\Tests\ValueObjects\Transporter;

use AzahariZaman\Huggingface\ValueObjects\Transporter\QueryParams;
use PHPUnit\Framework\TestCase;

final class QueryParamsTest extends TestCase
{
    public function testCreateReturnsEmptyParams(): void
    {
        $params = QueryParams::create();

        $this->assertInstanceOf(QueryParams::class, $params);
        $this->assertSame([], $params->toArray());
    }

    public function testWithParamAddsStringParam(): void
    {
        $params = QueryParams::create()->withParam('key', 'value');

        $this->assertSame(['key' => 'value'], $params->toArray());
    }

    public function testWithParamAddsIntParam(): void
    {
        $params = QueryParams::create()->withParam('limit', 10);

        $this->assertSame(['limit' => 10], $params->toArray());
    }

    public function testParamsAreImmutable(): void
    {
        $params1 = QueryParams::create();
        $params2 = $params1->withParam('key', 'value');

        $this->assertNotSame($params1, $params2);
        $this->assertSame([], $params1->toArray());
        $this->assertSame(['key' => 'value'], $params2->toArray());
    }

    public function testMultipleParamsCanBeAdded(): void
    {
        $params = QueryParams::create()
            ->withParam('param1', 'value1')
            ->withParam('param2', 100)
            ->withParam('param3', 'value3');

        $this->assertSame([
            'param1' => 'value1',
            'param2' => 100,
            'param3' => 'value3',
        ], $params->toArray());
    }

    public function testWithParamOverwritesExistingParam(): void
    {
        $params = QueryParams::create()
            ->withParam('key', 'original')
            ->withParam('key', 'updated');

        $this->assertSame(['key' => 'updated'], $params->toArray());
    }

    public function testWithParamAddsZeroValue(): void
    {
        $params = QueryParams::create()->withParam('count', 0);

        $this->assertSame(['count' => 0], $params->toArray());
    }

    public function testWithParamAddsEmptyString(): void
    {
        $params = QueryParams::create()->withParam('empty', '');

        $this->assertSame(['empty' => ''], $params->toArray());
    }

    public function testToArrayReturnsEmptyArrayForNewInstance(): void
    {
        $params = QueryParams::create();

        $this->assertIsArray($params->toArray());
        $this->assertEmpty($params->toArray());
    }
}
