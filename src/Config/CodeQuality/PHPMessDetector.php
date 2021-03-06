<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use Twig\Environment as Twig;

class PHPMessDetector implements MakefileConfigInterface, FinishConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/PHPMessDetector/Makefile.twig');
    }

    public function getBitbucketPipelinesStep(): string
    {
        return $this->twig->render('Config/CodeQuality/PHPMessDetector/bitbucket-pipelines.yml.twig');
    }
}
