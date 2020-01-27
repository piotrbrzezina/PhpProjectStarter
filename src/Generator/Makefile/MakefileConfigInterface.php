<?php

declare(strict_types=1);

namespace App\Generator\Makefile;

use App\Config\ConfigCollection;

interface MakefileConfigInterface
{
    public function getMakefileContent(ConfigCollection $configCollection): string;
}
