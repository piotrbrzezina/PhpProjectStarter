<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesPullRequestConfigInterface;
use App\Generator\BitbucketPipelines\BitbucketPipelinesTagConfigInterface;
use Twig\Environment as Twig;

class BitbucketPipelinesCodeQualityConfig implements BitbucketPipelinesConfigInterface, BitbucketPipelinesPullRequestConfigInterface, BitbucketPipelinesTagConfigInterface, InitialConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/BitbucketPipelines/bitbucket-pipelines.yml.twig');
    }

    public function getPullRequestsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/BitbucketPipelines/pull-requests-bitbucket-pipelines.yml.twig');
    }

    public function getPullRequestsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return '';
    }

    public function getTagsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/BitbucketPipelines/tag-bitbucket-pipelines.yml.twig');
    }

    public function getTagsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return '';
    }
}
