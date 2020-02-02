<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\Dockerfile\DockerfileBuildStepsConfigInterface;
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

final class SymfonyWebsiteSkeletonConfig implements
    ShelCommandConfigInterface,
    PhpExtensionsConfigInterface,
    DockerfileBuildStepsConfigInterface,
    DockerComposeConfigInterface,
    NginxConfigInterface,
    PhpIniConfigInterface,
    MakefileConfigInterface
{
    private Twig $twig;
    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        $this->twig = $twig;
        $this->projectPath = $projectPath;
    }

    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'create-project', '--working-dir', $this->projectPath, '--prefer-dist', 'symfony/website-skeleton', '.'],
            ['composer', 'require', '--working-dir', $this->projectPath, '--dev', '--no-scripts', 'symfony/profiler-pack', 'symfony/debug-bundle'],
        ];
    }

    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return ['php-fpm', 'php'];
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getDockerfileBuildSteps(ConfigCollection $configCollection): string
    {
        $phpExtensions = PhpExtensionsHelper::getPhpExtensions($configCollection);

        return $this->twig->render(
            'Config/Framework/SymfonyWebsiteSkeleton/Dockerfile.twig',
            compact('phpExtensions')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getDockerComposeData(ConfigCollection $configCollection): string
    {
        $projectName = 'defaultProjectName';
        $clientName = 'defaultClientName';

        if ($configurator = ProjectHelper::getProject($configCollection)) {
            $projectName = $configurator->getName();
            $clientName = $configurator->getClientName();
        }

        return $this->twig->render(
            'Config/Framework/SymfonyWebsiteSkeleton/docker-compose.yaml.twig',
            compact('projectName', 'clientName')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getNginxConfig(ConfigCollection $configCollection): string
    {
        $clientMaxBodySize = 16;
        if ($configurator = UploadFileSizeHelper::getUploadFileSize($configCollection)) {
            $clientMaxBodySize = $configurator->getMaxBodySize();
        }

        return $this->twig->render(
            'Config/Framework/SymfonyWebsiteSkeleton/nginx.conf.twig',
            compact('clientMaxBodySize')
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getPhpIniConfig(ConfigCollection $configCollection): string
    {
        $uploadMaxFileSize = 8;
        $clientMaxBodySize = 16;
        if ($configurator = UploadFileSizeHelper::getUploadFileSize($configCollection)) {
            $clientMaxBodySize = $configurator->getMaxBodySize();
            $uploadMaxFileSize = $configurator->getMaxUploadFileSize();
        }

        return $this->twig->render(
            'Config/Framework/SymfonyWebsiteSkeleton/php.ini.twig',
            compact('clientMaxBodySize', 'uploadMaxFileSize')
        );
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Framework/SymfonyWebsiteSkeleton/Makefile.twig');
    }
}
