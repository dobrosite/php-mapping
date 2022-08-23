<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\CollectionType;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\FloatType;
use DobroSite\Mapping\SameType;
use DobroSite\Mapping\Type;

/**
 * @covers \DobroSite\Mapping\CollectionType
 */
final class CollectionTypeTest extends TypeTestCase
{
    public static function toPhpDataProvider(): iterable
    {
        return [
            SameType::class => [
                'givenValue' => ['foo', 'bar'],
                'expectedValue' => ['foo', 'bar'],
                'arguments' => [new SameType()],
            ],
            FloatType::class => [
                'givenValue' => ['123.45', '54.321'],
                'expectedValue' => [123.45, 54.321],
                'arguments' => [
                    new FloatType(
                        new \NumberFormatter('en', \NumberFormatter::DEFAULT_STYLE)
                    ),
                ],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidValueType(): void
    {
        $type = new CollectionType(new SameType());

        $this->expectExceptionObject(
            new DataError(
                'Value for CollectionType should be an array, but NULL given.'
            )
        );

        $type->toPhpValue(null);
    }

    protected function createType(mixed ...$parameters): Type
    {
        return new CollectionType(...$parameters);
    }
}
