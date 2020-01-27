<?php

declare(strict_types=1);

namespace App\Generator\ShellCommand;

use App\Config\ConfigCollection;

interface ShelCommandConfigInterface
{
    /**
     * @param ConfigCollection $configCollection
     *
     * @return array[]
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array;
}
