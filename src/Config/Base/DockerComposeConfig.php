<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\DockerCompose\DockerComposeCiConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use Exception;
use Twig\Environment as Twig;

class DockerComposeConfig implements DockerComposeConfigInterface, DockerComposeCiConfigInterface, InitialConfigInterface
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
        return $this->twig->render('Config/Base/DockerCompose/docker-compose.yaml.twig');
    }

    public function getDockerComposeCiData(ConfigCollection $configCollection): string
    {
        return $this->getDockerComposeData($configCollection);
    }
}
