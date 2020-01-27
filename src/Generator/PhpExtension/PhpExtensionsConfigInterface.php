<?php

declare(strict_types=1);

namespace App\Generator\PhpExtension;

use App\Config\ConfigCollection;

interface PhpExtensionsConfigInterface
{
    /**
     * @return string[]
     */
    public function getPhpExtensions(ConfigCollection $configCollection): array;
}
