<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BidirectionalMapper;
use DobroSite\Mapping\BooleanType;
use DobroSite\Mapping\Exception\InvalidSourceType;
use DobroSite\Mapping\Exception\InvalidSourceValue;

/**
 * @covers \DobroSite\Mapping\BooleanType
 */
final class BooleanTypeTest extends BidirectionalTestCase
{
    public static function inputDataProvider(): iterable
    {
        return [
            'true' => [
                'given' => 'true',
                'expected' => true,
                'arguments' => [],
            ],
            'false' => [
                'given' => 'false',
                'expected' => false,
                'arguments' => [],
            ],
            'да → true' => [
                'given' => 'да',
                'expected' => true,
                'arguments' => ['да', 'нет'],
            ],
            'нет → false' => [
                'given' => 'нет',
                'expected' => false,
                'arguments' => ['да', 'нет'],
            ],
            '1 → true' => [
                'given' => 1,
                'expected' => true,
                'arguments' => ['1', '0'],
            ],
            '0 → false' => [
                'given' => 0,
                'expected' => false,
                'arguments' => ['1', '0'],
            ],
            'ДА → true (без учёта регистра)' => [
                'given' => 'ДА',
                'expected' => true,
                'arguments' => ['да', 'нет'],
            ],
            'НЕТ → false (без учёта регистра)' => [
                'given' => 'НЕТ',
                'expected' => false,
                'arguments' => ['да', 'нет'],
            ],
        ];
    }

    public static function outputDataProvider(): iterable
    {
        return [
            'true' => [
                'given' => true,
                'expected' => 'true',
                'arguments' => [],
            ],
            'false' => [
                'given' => false,
                'expected' => 'false',
                'arguments' => [],
            ],
            'да → true' => [
                'given' => true,
                'expected' => 'да',
                'arguments' => ['да', 'нет'],
            ],
            'нет → false' => [
                'given' => false,
                'expected' => 'нет',
                'arguments' => ['да', 'нет'],
            ],
            '1 → true' => [
                'given' => true,
                'expected' => 1,
                'arguments' => ['1', '0'],
            ],
            '0 → false' => [
                'given' => false,
                'expected' => 0,
                'arguments' => ['1', '0'],
            ],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidInputType(): void
    {
        $mapper = new BooleanType();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Value for the %s::input should be a scalar, but array (\n) given.",
                    BooleanType::class
                )
            )
        );

        $mapper->input([]);
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidInputValue(): void
    {
        $mapper = new BooleanType();

        $this->expectExceptionObject(
            new InvalidSourceValue(
                \sprintf(
                    'Value "Foo" is not allowed for the %s::input. Allowed values are "true" and "false".',
                    BooleanType::class
                )
            )
        );

        $mapper->input('Foo');
    }

    /**
     * @throws \Throwable
     */
    public function testInvalidOutputType(): void
    {
        $mapper = new BooleanType();

        $this->expectExceptionObject(
            new InvalidSourceType(
                \sprintf(
                    "Argument for the %s::output should be one of [boolean], but 'true' given.",
                    BooleanType::class
                )
            )
        );

        $mapper->output('true');
    }

    protected function createMapper(mixed ...$arguments): BidirectionalMapper
    {
        return new BooleanType(...$arguments);
    }
}
