<?php

declare(strict_types=1);

namespace App\Config;

use Psr\Log\InvalidArgumentException;

class DefaultConfigCollection
{
    /**
     * @var DefaultConfigInterface[]
     */
    private array $configs;

    /**
     * @param DefaultConfigInterface[] $configs
     */
    public function __construct(iterable $configs)
    {
        foreach ($configs as $config) {
            if (!$config instanceof DefaultConfigInterface) {
                throw new InvalidArgumentException();
            }
            $this->configs[get_class($config)] = $config;
        }
    }

    public function addConfigsToCollection(ConfigCollection $configCollection): void
    {
        foreach ($this->configs as $config) {
            $configCollection->add($config);
        }
    }
}
