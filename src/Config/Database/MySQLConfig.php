<?php

declare(strict_types=1);

namespace App\Config\Database;

use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use Exception;
use Twig\Environment as Twig;

class MySQLConfig implements DockerComposeConfigInterface, PhpExtensionsConfigInterface
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
        return $this->twig->render('Config/Database/MySQL/docker-compose.yaml.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return [
            'pdo_mysql',
        ];
    }
}
