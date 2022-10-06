<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\AsIs;
use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Collection;
use DobroSite\Mapping\Exception\InvalidSourceType;

/**
 * @covers \DobroSite\Mapping\Collection
 */
final class CollectionTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => ['foo', 'bar'],
                'expected' => ['FOO', 'BAR'],
                'arguments' => [
                    /** @noRector */
                    new Callback(input: \strtoupper(...), output: \strtolower(...)),
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => ['FOO', 'BAR'],
                'expected' => ['foo', 'bar'],
                'arguments' => [
                    /** @noRector */
                    new Callback(input: \strtoupper(...), output: \strtolower(...)),
                ],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new Collection(new AsIs());

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    Collection::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new Collection(new AsIs());

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [array], but 'foo' given.",
                    Collection::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new Collection(...$arguments);
    }
}
