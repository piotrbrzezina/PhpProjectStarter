<?php

declare(strict_types=1);

namespace App\Config\Framework;

use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\Dockerfile\DockerfileBuildStepsConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Exception;
use Twig\Environment as Twig;

final class LaravelConfig implements PhpExtensionsConfigInterface, DockerfileBuildStepsConfigInterface, ShelCommandConfigInterface, DockerComposeConfigInterface
{
    private Twig $twig;
    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        $this->twig = $twig;
        $this->projectPath = $projectPath;
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
        return $this->twig->render('Config/Framework/SymfonyWebsiteSkeleton/docker-compose.yaml.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        return [
            ['composer', 'create-project', '--working-dir', $this->projectPath, '--prefer-dist', ' laravel/laravel', '.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return [
            'php-fpm',
            'php',
        ];
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
        return $this->twig->render('Config/Framework/Laravel/docker-compose.yaml.twig');
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Framework/Laravel/Makefile.twig');
    }
}
