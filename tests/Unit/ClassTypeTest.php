<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\ClassType;
use DobroSite\Mapping\ClassType\ObjectFactory;
use DobroSite\Mapping\ClassType\Properties;
use DobroSite\Mapping\ClassType\TargetClassResolver;
use DobroSite\Mapping\Type;
use Tests\Unit\Fixture\ClassWithConstructor;

/**
 * @covers \DobroSite\Mapping\ClassType
 */
final class ClassTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        yield \stdClass::class => [
            'givenValue' => [
                'foo' => 'foo',
                'bar' => 'bar',
            ],
            'expectedValue' => new ClassWithConstructor('foo', 'bar'),
            'parameters' => [
                new ClassType\ClassName(ClassWithConstructor::class),
                new ClassType\Properties(),
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testTryToMapNotAnArrayValue(): void
    {
        $type = new ClassType(
            new ClassType\ClassName(\stdClass::class),
            new Properties()
        );

        $this->expectExceptionObject(
            new \InvalidArgumentException(
                'Only array values can be mapped on objects, string given.'
            )
        );

        $type->toPhpValue('foo');
    }

    protected function createType(mixed ...$parameters): Type
    {
        $targetClassResolver = $parameters[0] ?? null;
        \assert($targetClassResolver instanceof TargetClassResolver);

        $properties = $parameters[1] ?? null;
        \assert($properties instanceof Properties);

        $factory = $parameters[2] ?? null;
        \assert($factory instanceof ObjectFactory || $factory === null);

        return new ClassType($targetClassResolver, $properties, $factory);
    }
}
