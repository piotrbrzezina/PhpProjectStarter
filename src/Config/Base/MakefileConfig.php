<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use Twig\Environment as Twig;

class MakefileConfig implements InitialConfigInterface, MakefileConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Makefile/Makefile.yaml');
    }
}
