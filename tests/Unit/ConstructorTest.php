<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\Constant;
use DobroSite\Mapping\Constructor;
use DobroSite\Mapping\Exception\InsufficientInput;
use DobroSite\Mapping\Exception\InvalidMapping;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\PublicProperties;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\ClassWithoutConstructor;
use Tests\Fixture\ClassWithPrivateConstructor;

/**
 * @covers \DobroSite\Mapping\Constructor
 * @covers \DobroSite\Mapping\AbstractObjectMapper
 */
final class ConstructorTest extends BidirectionalTestCase
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
                new Constant(ClassWithoutConstructor::class),
            ],
        ];

        $expected = new ClassWithConstructor('foo value', 'bar value');

        yield ClassWithConstructor::class => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                new Constant(ClassWithConstructor::class),
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
                new Constant(ClassWithoutConstructor::class),
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testClassNameIsNotString(): void
    {
        $mapper = new Constructor(new Constant(null));

        $this->expectExceptionObject(
            new InvalidMapping(
                \sprintf(
                    '%s cannot create object: class name should be a string, but NULL returned by class name mapper.',
                    Constructor::class
                )
            )
        );

        $mapper->input([]);
    }

    /**
     * @throws \Throwable
     */
    public function testClassNotExists(): void
    {
        $mapper = new Constructor(new Constant('NotExistedClass'));

        $this->expectExceptionObject(
            new InvalidMapping(
                \sprintf(
                    '%s cannot create object. Class "NotExistedClass" does not exist',
                    Constructor::class
                )
            )
        );

        $mapper->input([]);
    }

    public function testInvalidInputType(): void
    {
        $mapper = new Constructor(new Constant(input: \stdClass::class));

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    Constructor::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new Constructor(new Constant(input: \stdClass::class));

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [object], but array (\n  0 => 'foo',\n) given.",
                    PublicProperties::class
                )
            )
        );

        $mapper->output(['foo']);
    }

    /**
     * @throws \Throwable
     */
    public function testPrivateConstructor(): void
    {
        $mapper = new Constructor(new Constant(ClassWithPrivateConstructor::class));

        $this->expectExceptionObject(
            new InvalidMapping(
                \sprintf(
                    '%s::__construct is not public. Try another factory.',
                    ClassWithPrivateConstructor::class
                )
            )
        );

        $mapper->input([]);
    }

    /**
     * @throws \Throwable
     */
    public function testValueForArgumentNotExists(): void
    {
        $mapper = new Constructor(new Constant(ClassWithConstructor::class));

        $this->expectExceptionObject(
            new InsufficientInput('There is no "bar" field in the input data.')
        );

        $mapper->input(['foo' => 'foo value']);
    }

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new Constructor(...$arguments);
    }
}
