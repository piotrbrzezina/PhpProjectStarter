<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;

class ComposerConfig implements ShelCommandConfigInterface, FinishConfigInterface
{
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'install'],
        ];
    }
}
