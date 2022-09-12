<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

interface Mapper
{
    /**
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
     */
    public function input(mixed $source): mixed;

    /**
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \UnexpectedValueException
     */
    public function output(mixed $source): mixed;
}
