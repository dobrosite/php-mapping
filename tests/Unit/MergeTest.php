<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Constant;
use DobroSite\Mapping\Merge;
use DobroSite\Mapping\OutputMapper;

/**
 * @covers \DobroSite\Mapping\Merge
 */
final class MergeTest extends OutputMapperTestCase
{
    public static function outputDataProvider(): iterable
    {
        yield 'foo+bar' => [
            'given' => null,
            'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'arguments' => [
                new Constant(output: ['foo' => 'foo value']),
                new Constant(output: ['bar' => 'bar value']),
            ],
        ];
    }

    public function testInvalidOutputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    protected function createMapper(mixed ...$arguments): OutputMapper
    {
        return new Merge(...$arguments);
    }
}
