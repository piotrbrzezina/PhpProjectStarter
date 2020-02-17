<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\FinishConfigInterface;
use App\Generator\Git\GitIgnoreConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\Mutagen\MutagenConfigInterface;
use Twig\Environment as Twig;

class MutagenConfig implements FinishConfigInterface, MakefileConfigInterface, MutagenConfigInterface, GitIgnoreConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Mutagen/Makefile.twig');
    }

    public function getMutagenContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Mutagen/mutagen.yml.dist.twig');
    }

    public function getGitIgnoreContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/Mutagen/gitignore.twig');
    }
}
