<?php

declare(strict_types=1);

namespace Tests\Unit\Type;

use DobroSite\Mapping\Type\SameType;
use DobroSite\Mapping\Type\Type;
use PHPUnit\Framework\TestCase;

abstract class TypeTestCase extends TestCase
{
    /**
     * Поставщик данных для {@see testToPhpValue}.
     *
     * @return iterable<string, array>
     *
     * @throws \Throwable
     */
    abstract public static function toPhpDataProvider(): iterable;

    /**
     * @throws \Throwable
     *
     * @dataProvider toPhpDataProvider
     */
    public function testToPhpValue(mixed $givenValue, mixed $expectedValue): void
    {
        self::assertSame(
            $expectedValue,
            $this->createType()->toPhpValue($givenValue)
        );
    }

    /**
     * Метод должен создавать экземпляр проверяемого типа.
     */
    abstract protected function createType(): Type;
}
