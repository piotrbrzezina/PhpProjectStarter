<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Config\ConfigCollection;
use App\Generator\Database\DatabaseConnectionConfig;
use App\Generator\Database\DatabaseSqlConnectionConfigInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use Exception;
use Twig\Environment as Twig;

class PostgresConfigSql implements DockerComposeConfigInterface, DockerComposeCiConfigInterface, PhpExtensionsConfigInterface, DatabaseSqlConnectionConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
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
            'Config/Database/Postgres/docker-compose.yaml.twig',
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

    public function getConnectionData(ConfigCollection $configCollection): DatabaseConnectionConfig
    {
        return new DatabaseConnectionConfig(
            'postgresql',
            'database',
            '5432',
            '11',
            'dbuser',
            'dbpass',
            'dbname',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return [
            'pdo_pgsql',
        ];
    }
}
