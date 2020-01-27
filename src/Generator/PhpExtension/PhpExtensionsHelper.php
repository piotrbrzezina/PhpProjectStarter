<?php

declare(strict_types=1);

namespace App\Generator\PhpExtension;

use App\Config\ConfigCollection;

class PhpExtensionsHelper
{
    /**
     * @param ConfigCollection $configCollection
     *
     * @return string[]
     */
    public static function getPhpExtensions(ConfigCollection $configCollection): array
    {
        $phpExtensions = [];
        /** @var PhpExtensionsConfigInterface $configurator */
        foreach ($configCollection->get(PhpExtensionsConfigInterface::class) as $configurator) {
            $phpExtensions = array_merge($phpExtensions, $configurator->getPhpExtensions($configCollection));
        }

        return $phpExtensions;
    }
}
