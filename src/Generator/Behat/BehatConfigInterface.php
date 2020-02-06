<?php

declare(strict_types=1);

namespace App\Generator\Behat;

use App\Config\ConfigCollection;

interface BehatConfigInterface
{
    public function getBehatConfig(ConfigCollection $configCollection): string;

    /**
     * @param ConfigCollection $configCollection
     *
     * @return BehatFeatureFileInterface[]
     */
    public function getBehatFeatureFile(ConfigCollection $configCollection): array;
}
