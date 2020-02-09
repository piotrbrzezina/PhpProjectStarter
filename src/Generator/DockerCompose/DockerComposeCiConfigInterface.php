<?php

declare(strict_types=1);

namespace App\Generator\DockerCompose;

use App\Config\ConfigCollection;

interface DockerComposeCiConfigInterface
{
    public function getDockerComposeCiData(ConfigCollection $configCollection): string;
}
