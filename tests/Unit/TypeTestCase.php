<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Type;
use PHPUnit\Framework\TestCase;

abstract class TypeTestCase extends TestCase
{
    /**
     * Поставщик данных для {@see testToPhpValue}.
     *
     * @return iterable<string, array<mixed>>
     *
     * @throws \Throwable
     */
    abstract public static function toPhpDataProvider(): iterable;

    /**
     * @param array<mixed> $createTypeParams
     *
     * @throws \Throwable
     *
     * @dataProvider toPhpDataProvider
     */
    public function testToPhpValue(
        mixed $givenValue,
        mixed $expectedValue,
        array $createTypeParams = []
    ): void {
        self::assertEquals(
            $expectedValue,
            $this->createType(...$createTypeParams)->toPhpValue($givenValue)
        );
    }

    /**
     * Метод должен создавать экземпляр проверяемого типа.
     */
    abstract protected function createType(mixed ...$parameters): Type;
}
