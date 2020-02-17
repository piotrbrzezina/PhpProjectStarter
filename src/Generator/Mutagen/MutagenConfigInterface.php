<?php

declare(strict_types=1);

namespace App\Generator\Mutagen;

use App\Config\ConfigCollection;

interface MutagenConfigInterface
{
    public function getMutagenContent(ConfigCollection $configCollection): string;
}
