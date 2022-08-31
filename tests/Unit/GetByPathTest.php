<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\BooleanType;
use DobroSite\Mapping\Exception\DataError;
use DobroSite\Mapping\Type;
use PHPUnit\Framework\TestCase;

use function DobroSite\Mapping\getByPath;

/**
 * @covers \DobroSite\Mapping\getByPath
 */
final class GetByPathTest extends TestCase
{
    public static function dataProvider(): iterable
    {
        $data = [
            'foo' => 'bar',
            'a' => [
                'b' => [
                    'c' => 'ABC',
                ],
            ],
        ];

        return [
            [
                'data' => $data,
                'path' => 'foo',
                'expectedValue' => 'bar',
            ],
            [
                'data' => $data,
                'path' => 'a',
                'expectedValue' => ['b' => ['c' => 'ABC']],
            ],
            [
                'data' => $data,
                'path' => 'a.b',
                'expectedValue' => ['c' => 'ABC'],
            ],
            [
                'data' => $data,
                'path' => 'a.b.c',
                'expectedValue' => 'ABC',
            ],
        ];
    }

    /**
     * @throws \Throwable
     *
     * @dataProvider dataProvider
     */
    public function testGetByPath(array $data, string $path, mixed $expectedValue): void
    {
        self::assertSame($expectedValue, getByPath($path, $data));
    }

    /**
     * @throws \Throwable
     */
    public function testPathNotExists(): void
    {
        $this->expectExceptionObject(new DataError('Part "bar" of path "foo.bar" does not exists.'));
        getByPath('foo.bar', ['foo' => []]);
    }
}
