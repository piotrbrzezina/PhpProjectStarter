<?php

declare(strict_types=1);

namespace App\Generator\PhpIni;

use App\Config\ConfigCollection;

interface PhpIniConfigInterface
{
    public function getPhpIniConfig(ConfigCollection $configCollection): string;
}
