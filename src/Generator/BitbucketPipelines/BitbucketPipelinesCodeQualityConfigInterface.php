<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;

interface BitbucketPipelinesCodeQualityConfigInterface
{
    public function getCodeQualityBitbucketPipelines(ConfigCollection $configCollection): string;
}
