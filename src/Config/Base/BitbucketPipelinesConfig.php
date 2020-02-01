<?php
declare(strict_types=1);

namespace App\Config\Base;


use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesConfigInterface;
use Twig\Environment as Twig;

class BitbucketPipelinesConfig implements BitbucketPipelinesConfigInterface, InitialConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getCodeQualityBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/BitbucketPipelines/bitbucket-pipelines.yml.twig');
    }
}