<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Apply;
use DobroSite\Mapping\AsIs;
use DobroSite\Mapping\Callback;
use DobroSite\Mapping\Constant;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\FloatType;
use DobroSite\Mapping\Mapper;

/**
 * @covers \DobroSite\Mapping\Apply
 */
final class ApplyTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            [
                'given' => 'foo',
                'expected' => 'bar',
                'arguments' => [
                    'input' => new Callback(
                        fn($src) => \is_numeric($src) ? new AsIs() : new Constant('bar')
                    ),
                ],
            ],
            [
                'given' => '123',
                'expected' => '123',
                'arguments' => [
                    'input' => new Callback(
                        fn($src) => \is_numeric($src) ? new AsIs() : new Constant('bar')
                    ),
                ],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            [
                'given' => 'foo',
                'expected' => 'bar',
                'arguments' => [
                    'output' => new Callback(
                        output: fn($s) => \is_numeric($s) ? new AsIs() : new Constant(output: 'bar')
                    ),
                ],
            ],
            [
                'given' => '123',
                'expected' => '123',
                'arguments' => [
                    'output' => new Callback(
                        output: fn($s) => \is_numeric($s) ? new AsIs() : new Constant(output: 'bar')
                    ),
                ],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidInputMapper(): void
    {
        $mapper = new Apply(new Constant('foo'));

        $this->expectExceptionObject(
            new InvalidMapping(
                \sprintf(
                    "Mapper passed to %s::__construct should return instance of %s, but it returned 'foo'.",
                    Apply::class,
                    Mapper::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidInputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidOutputMapper(): void
    {
        $mapper = new Apply(new Constant('foo'));

        $this->expectExceptionObject(
            new InvalidMapping(
                \sprintf(
                    "Mapper passed to %s::__construct should return instance of %s, but it returned 'foo'.",
                    Apply::class,
                    Mapper::class
                )
            )
        );

        $mapper->output('foo');
    }

    public function testInvalidOutputType(): void
    {
        $this->expectNotToPerformAssertions();
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new Apply(...$arguments);
    }
}
