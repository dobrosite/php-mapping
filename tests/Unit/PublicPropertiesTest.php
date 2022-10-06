<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\OutputMapper;
use DobroSite\Mapping\PublicProperties;
use Tests\Fixture\ClassWithConstructor;

/**
 * @covers \DobroSite\Mapping\PublicProperties
 */
final class PublicPropertiesTest extends OutputMapperTestCase
{
    public static function outputDataProvider(): iterable
    {
        $object = new ClassWithConstructor('foo value', 'bar value');

        yield 'Есть только публичные свойства' => [
            'given' => $object,
            'expected' => ['foo' => 'foo value', 'bar' => 'bar value'],
            'arguments' => [],
        ];
    }

    public function testInvalidOutputType(): void
    {
        $mapper = new PublicProperties();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [object], but 'foo' given.",
                    PublicProperties::class
                )
            )
        );

        $mapper->output('foo');
    }

    protected function createMapper(mixed ...$arguments): OutputMapper
    {
        return new PublicProperties();
    }
}
