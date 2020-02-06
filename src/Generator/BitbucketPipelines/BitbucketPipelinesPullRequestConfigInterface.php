<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;

interface BitbucketPipelinesPullRequestConfigInterface
{
    public function getPullRequestsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string;

    public function getPullRequestsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string;
}
