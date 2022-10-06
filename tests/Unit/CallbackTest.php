<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\Callback
 */
final class CallbackTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            'FOO → foo' => [
                'given' => 'FOO',
                'expected' => 'foo',
                'arguments' => [
                    /** @noRector */
                    \strtolower(...),
                    /** @noRector */
                    \strtoupper(...),
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            'foo → FOO' => [
                'given' => 'foo',
                'expected' => 'FOO',
                'arguments' => [
                    /** @noRector */
                    \strtolower(...),
                    /** @noRector */
                    \strtoupper(...),
                ],
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

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new Callback(...$arguments);
    }
}
