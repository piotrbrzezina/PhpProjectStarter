<?php

declare(strict_types=1);

namespace App\Generator\ProjectName;

use App\Config\ConfigCollection;

class ProjectHelper
{
    public static function getProject(ConfigCollection $configCollection): ?ProjectInterface
    {
        $configs = $configCollection->get(ProjectInterface::class);
        if (empty($configs)) {
            return null;
        }

        $projectName = array_pop($configs);
        if (!$projectName instanceof ProjectInterface) {
            return null;
        }

        return $projectName;
    }
}
