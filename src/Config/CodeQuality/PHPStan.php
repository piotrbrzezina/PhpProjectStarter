<?php

declare(strict_types=1);

namespace App\Config\CodeQuality;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\CodeQuality\PHPStanConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use Twig\Environment as Twig;

class PHPStan implements MakefileConfigInterface, FinishConfigInterface, PHPStanConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/PHPStan/Makefile.twig');
    }

    public function getBitbucketPipelinesStep(): string
    {
        return $this->twig->render('Config/CodeQuality/PHPStan/bitbucket-pipelines.yml.twig');
    }

    public function getPhpStanConfig(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/CodeQuality/PHPStan/phpstan.neon.twig');
    }
}
