<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Generator\Behat\BehatFeatureFileInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\Dockerfile\DockerfileBuildStepsConfigInterface;
use App\Generator\NginxConfig\NginxConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsHelper;
use App\Generator\PhpIni\PhpIniConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use App\Generator\UploadFileSize\UploadFileSizeHelper;
use Exception;
use Twig\Environment as Twig;

final class SymfonySkeletonConfig extends SymfonyConfig implements ShelCommandConfigInterface
{
    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        parent::__construct($twig);
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
