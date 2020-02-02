<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Exception;
use Twig\Environment as Twig;

class MongoDBConfig implements DockerComposeConfigInterface, PhpExtensionsConfigInterface, ShelCommandConfigInterface
{
    private Twig $twig;
    private string $projectPath;

    public function __construct(Twig $twig, string $projectPath)
    {
        $this->twig = $twig;
        $this->projectPath = $projectPath;
    }

    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return ['mongodb'];
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
        return $this->twig->render('Config/Database/MongoDB/docker-compose.yaml.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        if ($configCollection->has(SymfonyWebsiteSkeletonConfig::class) || $configCollection->has(SymfonySkeletonConfig::class)) {
            return [
                ['composer', 'require', '--working-dir', $this->projectPath, '--no-scripts', 'doctrine/mongodb-odm-bundle'],
            ];
        }
    }
}
