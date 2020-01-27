<?php

declare(strict_types=1);

namespace App\Generator;

use Psr\Log\InvalidArgumentException;

class GeneratorProvider
{
    /**
     * @var GeneratorInterface[]
     */
    protected array $generators = [];

    /**
     * @param GeneratorInterface[] $generators
     */
    public function __construct(iterable $generators)
    {
        foreach ($generators as $generator) {
            if (!$generator instanceof GeneratorInterface) {
                throw new InvalidArgumentException();
            }
            $this->generators[] = $generator;
        }
    }

    /**
     * @return GeneratorInterface[]
     */
    public function getGenerators(): array
    {
        return $this->generators;
    }
}
