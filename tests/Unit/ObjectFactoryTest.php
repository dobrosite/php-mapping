<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Mapper;
use DobroSite\Mapping\ObjectFactory;
use Tests\Fixture\ClassWithConstructor;
use Tests\Fixture\SyntheticConstructor;
use Tests\Fixture\TestFactory;

use function Tests\Fixture\test_factory_function;

/**
 * @covers \DobroSite\Mapping\ObjectFactory
 * @covers \DobroSite\Mapping\ObjectMapper
 */
final class ObjectFactoryTest extends MapperTestCase
{
    public static function inputDataProvider(): iterable
    {
        $expected = new ClassWithConstructor('foo value', 'bar value');

        yield "'function_name'" => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                '\Tests\Fixture\test_factory_function',
            ],
        ];

        yield 'function_name(...)' => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                test_factory_function(...),
            ],
        ];

        yield 'function() {}' => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                /**
                 * @noRector \Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector
                 * @noRector \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector
                 */
                function (string $foo, string $bar): object {
                    return new ClassWithConstructor($foo, $bar);
                },
            ],
        ];

        yield 'fn()' => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                fn(string $foo, string $bar) => new ClassWithConstructor($foo, $bar),
            ],
        ];

        $expected = SyntheticConstructor::new('foo value', 'bar value');

        yield "['Factory', 'method']" => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                [SyntheticConstructor::class, 'new'],
            ],
        ];

        $factory = new TestFactory();
        $expected = $factory->create();
        $expected->foo = 'foo value';
        $expected->bar = 'bar value';

        yield "[\$factory, 'method']" => [
            'given' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'expected' => $expected,
            'arguments' => [
                /**
                 * @noRector \Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector
                 * @noRector \Rector\Php81\Rector\Array_\FirstClassCallableRector
                 */
                [$factory, 'create'],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [];
    }

    public function testInvalidInputType(): void
    {
        $mapper = new ObjectFactory(fn() => null);

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    "Argument for the %s::input should be one of [array], but 'foo' given.",
                    ObjectFactory::class
                )
            )
        );

        $mapper->input('foo');
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new ObjectFactory(fn() => null);

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                \sprintf(
                    "Argument for the %s::input should be one of [object], but array (\n  0 => 'foo',\n) given.",
                    ObjectFactory::class
                )
            )
        );

        $mapper->output(['foo']);
    }

    public function testOutput(
        mixed $given = null,
        mixed $expected = null,
        array $mapperConstructorArgs = []
    ): void {
        $this->expectNotToPerformAssertions();
    }

    protected function createMapper(mixed ...$arguments): Mapper
    {
        return new ObjectFactory(...$arguments);
    }
}
