<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Constant;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\Constant
 */
final class ConstantTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testInput(): void
    {
        $mapper = new Constant(input: 'foo');

        self::assertSame('foo', $mapper->input(\uniqid('', false)));
    }

    /**
     * @throws \Throwable
     */
    public function testOutput(): void
    {
        $mapper = new Constant(output: 'foo');

        self::assertSame('foo', $mapper->output(\uniqid('', false)));
    }
}
