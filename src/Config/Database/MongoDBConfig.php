<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\Database\DatabaseConnectionConfig;
use App\Generator\Database\DatabaseMongoConnectionConfigInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Exception;
use Twig\Environment as Twig;

class MongoDBConfig implements DockerComposeConfigInterface, DockerComposeCiConfigInterface, PhpExtensionsConfigInterface, ShelCommandConfigInterface, DatabaseMongoConnectionConfigInterface
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
        $config = $this->getConnectionData($configCollection);

        return $this->twig->render(
            'Config/Database/MongoDB/docker-compose.yaml.twig',
            [
                'host' => $config->getHost(),
                'version' => $config->getVersion(),
                'user' => $config->getUser(),
                'password' => $config->getPassword(),
                'databaseName' => $config->getDatabaseName(),
            ]
        );
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getDockerComposeCiData(ConfigCollection $configCollection): string
    {
        return $this->getDockerComposeData($configCollection);
    }

    /**
     * {@inheritdoc}
     */
    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        $libraries = [];
        if ($configCollection->has(SymfonyWebsiteSkeletonConfig::class) || $configCollection->has(SymfonySkeletonConfig::class)) {
            $libraries[] = ['composer', 'config', 'extra.symfony.allow-contrib', 'true', '--working-dir', $this->projectPath];
            $libraries[] = ['composer', 'require', '--working-dir', $this->projectPath, 'doctrine/mongodb-odm-bundle'];
            $libraries[] = ['composer', 'config', 'extra.symfony.allow-contrib', 'false', '--working-dir', $this->projectPath];
        }

        return $libraries;
    }

    public function getConnectionData(ConfigCollection $configCollection): DatabaseConnectionConfig
    {
        return new DatabaseConnectionConfig(
            'mongodb',
            'database',
            '27017',
            '4.2.3',
            'dbuser',
            'dbpass',
            'dbname',
        );
    }
}
