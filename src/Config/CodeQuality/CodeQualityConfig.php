<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use Twig\Environment as Twig;

class CodeQualityConfig implements DockerComposeConfigInterface, FinishConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getDockerComposeData(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/docker-compose.yaml.twig');
    }
}
