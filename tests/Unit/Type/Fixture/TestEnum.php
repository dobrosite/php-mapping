<?php

declare(strict_types=1);

namespace Tests\Unit\Type\Fixture;

enum TestEnum: string
{
    case Foo = 'foo';

    case Bar = 'bar';
}
