<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use Exception;
use Twig\Environment as Twig;

class PostgresConfig implements DockerComposeConfigInterface, DockerComposeCiConfigInterface, PhpExtensionsConfigInterface
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
        return $this->twig->render('Config/Database/Postgres/docker-compose.yaml.twig', $this->getConnectionData());
    }

    /**
     * @param ConfigCollection $configCollection
     * @return string
     * @throws Exception
     */
    public function getDockerComposeCiData(ConfigCollection $configCollection): string
    {
        return $this->getDockerComposeData($configCollection);
    }

    /**
     * @return string[]
     */
    public function getConnectionData(): array
    {
        return
            [
                'host' => 'database',
                'port' => '5432',
                'version' => '11',
                'user' => 'dbuser',
                'password' => 'dbpass',
                'databaseName' => 'dbname',
            ];
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
