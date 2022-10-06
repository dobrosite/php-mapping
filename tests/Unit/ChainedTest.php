<?php

declare(strict_types=1);

namespace Tests\Unit;

use DobroSite\Mapping\Chained;
use DobroSite\Mapping\InputMapper;
use DobroSite\Mapping\Mapper;
use DobroSite\Mapping\OutputMapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \DobroSite\Mapping\Chained
 */
final class ChainedTest extends TestCase
{
    /**
     * @throws \Throwable
     */
    public function testInput(): void
    {
        $innerMapper1 = $this->createMock(InputMapper::class);
        $innerMapper2 = $this->createMock(Mapper::class);
        $innerMapper3 = $this->createMock(InputMapper::class);

        $mapper = new Chained(
            $innerMapper1,
            $innerMapper2,
            $innerMapper3,
        );

        $innerMapper1
            ->expects(self::once())
            ->method('input')
            ->with(self::identicalTo('INPUT'))
            ->willReturn('OUTPUT1');

        $innerMapper3
            ->expects(self::once())
            ->method('input')
            ->with(self::identicalTo('OUTPUT1'))
            ->willReturn('OUTPUT3');

        $output = $mapper->input('INPUT');

        self::assertSame('OUTPUT3', $output);
    }

    /**
     * @throws \Throwable
     */
    public function testOutput(): void
    {
        $innerMapper1 = $this->createMock(OutputMapper::class);
        $innerMapper2 = $this->createMock(Mapper::class);
        $innerMapper3 = $this->createMock(OutputMapper::class);

        $mapper = new Chained(
            $innerMapper1,
            $innerMapper2,
            $innerMapper3,
        );

        $innerMapper3
            ->expects(self::once())
            ->method('output')
            ->with(self::identicalTo('INPUT'))
            ->willReturn('OUTPUT3');

        $innerMapper1
            ->expects(self::once())
            ->method('output')
            ->with(self::identicalTo('OUTPUT3'))
            ->willReturn('OUTPUT1');

        $output = $mapper->output('INPUT');

        self::assertSame('OUTPUT1', $output);
    }
}
