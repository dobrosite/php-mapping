<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\Map;
use DobroSite\Mapping\Nullable;

/**
 * @covers \DobroSite\Mapping\Nullable
 */
final class NullableTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        $mainType = new Map(['foo' => 'bar']);

        return [
            'null → null' => [
                'givenValue' => null,
                'expectedValue' => null,
                'arguments' => [$mainType],
            ],
            'foo → bar' => [
                'givenValue' => 'foo',
                'expectedValue' => 'bar',
                'arguments' => [$mainType],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        $mapper = new Map(['foo' => 'bar']);

        return [
            'null ← null' => [
                'givenValue' => null,
                'expectedValue' => null,
                'arguments' => [$mapper],
            ],
            'foo ← bar' => [
                'givenValue' => 'bar',
                'expectedValue' => 'foo',
                'arguments' => [$mapper],
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    public function testInvalidOutputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new Nullable(...$arguments);
    }
}
