<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\Constructor;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\InputMapper;
use DobroSite\Mapping\ObjectMapper;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\ClassWithoutConstructor;

/**
 * @covers \DobroSite\Mapping\ObjectMapper
 */
final class ObjectMapperTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        $expected = new ClassWithoutConstructor();
        $expected->foo = 'foo value';
        $expected->bar = 'bar value';

        yield ClassWithoutConstructor::class => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                new Constructor(ClassWithoutConstructor::class),
            ],
        ];

        $expected = new ClassWithConstructor('foo value', 'bar value');

        yield ClassWithConstructor::class => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                new Constructor(ClassWithConstructor::class),
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        $given = new ClassWithoutConstructor();
        $given->foo = 'foo value';
        $given->bar = 'bar value';

        yield ClassWithoutConstructor::class => [
            'given' => $given,
            'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'arguments' => [
                new Constructor(ClassWithoutConstructor::class),
            ],
        ];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ObjectMapper($this->createStub(InputMapper::class));

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ObjectMapper::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new ObjectMapper($this->createStub(InputMapper::class));

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [object], but array (\n  0 => 'foo',\n) given.",
                    ObjectMapper::class
                )
            )
        );

        $mapper->output(['foo']);
    }

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new ObjectMapper(...$arguments);
    }
}
