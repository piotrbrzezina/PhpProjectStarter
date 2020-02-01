<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use Twig\Environment as Twig;

class CodeQualityConfig implements DockerComposeConfigInterface, FinishConfigInterface, BitbucketPipelinesConfigInterface
{

    private Twig $twig;
    private EasyCodingStandardConfig $ecs;
    private PHPMessDetector $phpmd;
    private PHPStan $phpstan;

    public function __construct(Twig $twig, EasyCodingStandardConfig $ecs, PHPMessDetector $phpmd, PHPStan $phpstan)
    {
        $this->twig = $twig;
        $this->ecs = $ecs;
        $this->phpmd = $phpmd;
        $this->phpstan = $phpstan;
    }

    public function getDockerComposeData(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/docker-compose.yaml.twig');
    }

    public function getCodeQualityBitbucketPipelines(ConfigCollection $configCollection): string
    {
        $steps = [];
        $steps[] = $this->ecs->getBitbucketPipelinesStep($configCollection);
        $steps[] = $this->phpmd->getBitbucketPipelinesStep($configCollection);
        $steps[] = $this->phpstan->getBitbucketPipelinesStep($configCollection);

        return $this->twig->render('Config/CodeQuality/bitbucket-pipelines.yml.twig', compact('steps'));
    }
}
