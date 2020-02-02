<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Twig\Environment as Twig;

class BehatConfig implements ShelCommandConfigInterface, MakefileConfigInterface
{
    private string $projectPath;

    private Twig $twig;

    public function __construct(string $projectPath, Twig $twig)
    {
        $this->projectPath = $projectPath;
        $this->twig = $twig;
    }

    public function getShelCommandToRun(ConfigCollection $configCollection): array
    {
        $libraries = [];
        $libraries[] = ['composer', 'require', 'behat/behat:dev-master as 3.5.1', '--working-dir', $this->projectPath, '--dev', '--no-suggest'];
        if ($configCollection->has(SymfonySkeletonConfig::class) || $configCollection->has(SymfonyWebsiteSkeletonConfig::class)) {
            $libraries[] = ['composer', 'require', 'friends-of-behat/symfony-extension', '--working-dir', $this->projectPath, '--dev', '--no-suggest'];
            $libraries[] = ['composer', 'require', 'friends-of-behat/mink', '--working-dir', $this->projectPath, '--dev', '--no-suggest'];
            $libraries[] = ['composer', 'require', 'friends-of-behat/mink-extension', '--working-dir', $this->projectPath, '--dev', '--no-suggest'];

        }

        return $libraries;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/Behat/Makefile.twig');
    }
}
