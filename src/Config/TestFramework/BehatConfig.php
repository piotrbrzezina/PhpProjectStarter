<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Generator\ShellCommand\ShelCommandConfigInterface;

class BehatConfig implements ShelCommandConfigInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'require', 'behat/behat:dev-master as 3.5.1', '--working-dir', $this->projectPath, '--dev', '--no-suggest', '--no-scripts'],
        ];
    }
}
