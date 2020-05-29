<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesCodeQualityConfigInterface;
use App\Generator\DockerCompose\DockerComposeConfigInterface;
use Twig\Environment as Twig;

class CodeQualityCodeQualityConfig implements DockerComposeConfigInterface, FinishConfigInterface, BitbucketPipelinesCodeQualityConfigInterface
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
        $steps[] = $this->ecs->getBitbucketPipelinesStep();
        $steps[] = $this->phpmd->getBitbucketPipelinesStep();
        $steps[] = $this->phpstan->getBitbucketPipelinesStep();

        return $this->twig->render('Config/CodeQuality/bitbucket-pipelines.yml.twig', compact('steps'));
    }
}
