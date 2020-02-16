<?php

declare(strict_types=1);

namespace App\Generator\Git;

use App\Config\ConfigCollection;

interface GitIgnoreConfigInterface
{
    public function getGitIgnoreContent(ConfigCollection $configCollection): string;
}
