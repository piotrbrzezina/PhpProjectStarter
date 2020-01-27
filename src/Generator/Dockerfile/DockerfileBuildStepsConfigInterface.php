<?php

declare(strict_types=1);

namespace App\Generator\Dockerfile;

use App\Config\ConfigCollection;

interface DockerfileBuildStepsConfigInterface
{
    public function getDockerfileBuildSteps(ConfigCollection $configCollection): string;
}
