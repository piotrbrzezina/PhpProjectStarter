<?php

declare(strict_types=1);

namespace App\Config\Cache;

use App\Config\ConfigCollection;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use Exception;
use Twig\Environment as Twig;

class MemcachedConfig implements PhpExtensionsConfigInterface, DockerComposeConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return ['memcached'];
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
        return $this->twig->render('Config/Cache/Memcached/docker-compose.yaml.twig');
    }
}
