<?php

declare(strict_types=1);

namespace App\Generator\DockerCompose;

use App\Config\ConfigCollection;

interface DockerComposeConfigInterface
{
    public function getDockerComposeData(ConfigCollection $configCollection): string;
}
