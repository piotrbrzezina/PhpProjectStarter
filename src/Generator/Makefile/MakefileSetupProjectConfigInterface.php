<?php
declare(strict_types=1);

namespace App\Generator\Makefile;


use App\Config\ConfigCollection;

interface MakefileSetupProjectConfigInterface
{
    public function getSetupProjectStep(ConfigCollection $configCollection): string;
}