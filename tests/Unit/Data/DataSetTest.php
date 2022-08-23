<?php

declare(strict_types=1);

namespace Tests\Unit\Data;

use DobroSite\Mapping\Data\DataSet;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\Data\DataSet
 */
final class DataSetTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testGetByName(): void
    {
        $data = new DataSet(['foo' => 'FOO', 'bar' => 'BAR']);

        self::assertSame('FOO', $data->get('foo')?->value);
        self::assertSame('BAR', $data->get('bar')?->value);
    }

    /**
     * @throws \Throwable
     */
    public function testSetDefaultValue(): void
    {
        $data = new DataSet(['foo' => 'FOO']);
        $data->setDefaultValue('foo', 'OFF');
        $data->setDefaultValue('bar', 'BAR');

        self::assertSame('FOO', $data->get('foo')?->value);
        self::assertSame('BAR', $data->get('bar')?->value);
    }
}
