<?php

declare(strict_types=1);

namespace App\Generator\CodeQuality;

use App\Config\ConfigCollection;

interface EasyCodingStandardConfigInterface
{
    public function getEasyCodingStandardConfig(ConfigCollection $configCollection): string;
}
