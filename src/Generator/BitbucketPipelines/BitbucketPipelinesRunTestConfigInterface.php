<?php

declare(strict_types=1);

namespace App\Generator\BitbucketPipelines;

use App\Config\ConfigCollection;

interface BitbucketPipelinesRunTestConfigInterface
{
    public function getTestToRunOnBitbucketPipelines(ConfigCollection $configCollection): string;
}
