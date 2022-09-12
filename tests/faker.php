<?php

declare(strict_types=1);

namespace Tests;

use Faker\Factory;
use Faker\Generator;

function faker(): Generator
{
    return Factory::create();
}
