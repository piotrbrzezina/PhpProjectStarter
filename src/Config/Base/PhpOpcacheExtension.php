<?php

declare(strict_types=1);

namespace App\Config\Base;

use App\Config\ConfigCollection;
use App\Config\InitialConfigInterface;
use App\Generator\PhpExtension\PhpExtensionsConfigInterface;
use App\Generator\PhpIni\PhpIniConfigInterface;
use Twig\Environment as Twig;

class PhpOpcacheExtension implements InitialConfigInterface, PhpIniConfigInterface, PhpExtensionsConfigInterface
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function getPhpIniConfig(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/Base/PhpOpcacheExtension/php.ini.twig');
    }

    /**
     * {@inheritdoc}
     */
    public function getPhpExtensions(ConfigCollection $configCollection): array
    {
        return ['opcache'];
    }
}
