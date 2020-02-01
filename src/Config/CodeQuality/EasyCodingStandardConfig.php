<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesConfigInterface;
use App\Generator\CodeQuality\EasyCodingStandardConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use Exception;
use Twig\Environment as Twig;

class EasyCodingStandardConfig implements
    EasyCodingStandardConfigInterface,
    MakefileConfigInterface,
    FinishConfigInterface
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
    public function getEasyCodingStandardConfig(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/EasyCodingStandard/ecs.yaml.twig');
    }

    /**
     * @param ConfigCollection $configCollection
     *
     * @return string
     *
     * @throws Exception
     */
    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/EasyCodingStandard/Makefile.twig');
    }

    public function getBitbucketPipelinesStep(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/EasyCodingStandard/bitbucket-pipelines.yml.twig');
    }
}
