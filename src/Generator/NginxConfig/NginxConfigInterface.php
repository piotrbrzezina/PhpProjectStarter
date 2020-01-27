<?php

declare(strict_types=1);

namespace App\Generator\NginxConfig;

use App\Config\ConfigCollection;

interface NginxConfigInterface
{
    public function getNginxConfig(ConfigCollection $configCollection): string;
}
