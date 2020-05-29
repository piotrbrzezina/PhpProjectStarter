<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Twig\Environment as Twig;

final class SymfonySkeletonConfig extends SymfonyConfig implements ShelCommandConfigInterface
{
    private string $projectPath;

    public function __construct(Twig $twig, string $dockerPhpBasieImage, string $dockerWriteRepository, string $dockerReadRepository, string $dockerRepositoryPrefix, string $projectPath)
    {
        parent::__construct($twig, $dockerPhpBasieImage, $dockerWriteRepository, $dockerReadRepository, $dockerRepositoryPrefix);

        $this->projectPath = $projectPath;
    }

    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'create-project', '--working-dir', $this->projectPath, '--prefer-dist', 'symfony/skeleton', '.'],
            ['composer', 'require', '--working-dir', $this->projectPath, '--dev', '--no-scripts', 'symfony/profiler-pack', 'symfony/debug-bundle'],
        ];
    }
}
