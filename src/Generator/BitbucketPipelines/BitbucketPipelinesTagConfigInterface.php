<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;

interface BitbucketPipelinesTagConfigInterface
{
    public function getTagsBeforeTestBitbucketPipelines(ConfigCollection $configCollection): string;

    public function getTagsAfterTestBitbucketPipelines(ConfigCollection $configCollection): string;
}
