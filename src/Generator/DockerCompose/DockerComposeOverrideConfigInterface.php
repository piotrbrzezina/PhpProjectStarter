<?php

declare(strict_types=1);

namespace App\Generator\DockerCompose;

use App\Config\ConfigCollection;

interface DockerComposeOverrideConfigInterface
{
    public function getDockerComposeOverrideData(ConfigCollection $configCollection): string;
}
