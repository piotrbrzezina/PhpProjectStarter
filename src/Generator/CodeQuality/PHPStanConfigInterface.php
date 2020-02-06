<?php

declare(strict_types=1);

namespace App\Generator\CodeQuality;

use App\Config\ConfigCollection;

interface PHPStanConfigInterface
{
    public function getPhpStanConfig(ConfigCollection $configCollection): string;
}
