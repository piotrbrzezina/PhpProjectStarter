<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Config\ProjectName\ProjectConfig;
use App\Generator\Behat\BehatConfigInterface;
use App\Generator\Behat\BehatFeatureFile;
use App\Generator\BitbucketPipelines\BitbucketPipelinesPullRequestConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesTagConfigInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\DockerCompose\DockerComposeOverrideConfigInterface;
use App\Generator\Dockerfile\DockerfileBuildStepsConfigInterface;
use App\Generator\Git\GitIgnoreConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\NginxConfig\NginxConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsHelper;
use App\Generator\PhpIni\PhpIniConfigInterface;
use App\Generator\ProjectName\ProjectHelper;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use App\Generator\UploadFileSize\UploadFileSizeHelper;
use Exception;
use Twig\Environment as Twig;

final class SymfonyWebsiteSkeletonConfig extends SymfonyConfig implements ShelCommandConfigInterface
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
            ['composer', 'create-project', '--working-dir', $this->projectPath, '--prefer-dist', 'symfony/website-skeleton', '.'],
            ['composer', 'require', '--working-dir', $this->projectPath, '--dev', '--no-scripts', 'symfony/profiler-pack', 'symfony/debug-bundle'],
        ];
    }
}
