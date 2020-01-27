<?php

declare(strict_types=1);

namespace App\Generator\ProjectName;

use App\Config\ConfigCollection;

class ProjectNameHelper
{
    public static function getProjectName(ConfigCollection $configCollection): ?ProjectNameInterface
    {
        $configs = $configCollection->get(ProjectNameInterface::class);
        if (empty($configs)) {
            return null;
        }

        $projectName = array_pop($configs);
        if (!$projectName instanceof ProjectNameInterface) {
            return null;
        }

        return $projectName;
    }
}
