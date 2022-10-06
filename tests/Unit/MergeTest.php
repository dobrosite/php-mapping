<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Constant;
use DobroSite\Mapping\Exception\InvalidSourceType;
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
            'given' => ['foo' => 'foo value'],
            'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'arguments' => [
                new Constant(output: ['bar' => 'bar value']),
            ],
        ];
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new Merge();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [array], but 'foo' given.",
                    Merge::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): OutputMapper
    {
        return new Merge(...$arguments);
    }
}
