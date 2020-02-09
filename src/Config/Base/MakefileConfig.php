<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\Makefile\MakefileSetupProjectConfigInterface;
use Twig\Environment as Twig;

class MakefileConfig implements InitialConfigInterface, MakefileConfigInterface, MakefileSetupProjectConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Makefile/Makefile.twig');
    }

    public function getSetupProjectStep(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Makefile/MakefileSetupProjectStep.twig');
    }
}
