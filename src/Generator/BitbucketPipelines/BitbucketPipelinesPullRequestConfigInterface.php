<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;

interface BitbucketPipelinesPullRequestConfigInterface
{
    public function getPullRequestsBitbucketPipelines(ConfigCollection $configCollection): string;
}
