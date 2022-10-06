<?php

declare(strict_types=1);

namespace DobroSite\Mapping;

class Merge implements OutputMapper
{
    /**
     * @var OutputMapper[]
     */
    private readonly array $mappers;

    public function __construct(OutputMapper ...$mappers)
    {
        $this->mappers = $mappers;
    }

    public function output(mixed $source): array
    {
        checkSourceType($this, 'output', ['array'], $source);
        \assert(\is_array($source));

        $result = [$source];
        foreach ($this->mappers as $mapper) {
            $result[] = $mapper->output($source);
        }

        return \array_merge(...$result);
    }
}
