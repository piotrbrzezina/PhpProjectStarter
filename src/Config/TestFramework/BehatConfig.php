<?php

declare(strict_types=1);

namespace App\Config\TestFramework;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Generator\BitbucketPipelines\BitbucketPipelinesRunTestConfigInterface;
use App\Generator\Makefile\MakefileConfigInterface;
use App\Generator\ShellCommand\ShelCommandConfigInterface;
use Twig\Environment as Twig;

class BehatConfig implements ShelCommandConfigInterface, MakefileConfigInterface, BitbucketPipelinesRunTestConfigInterface
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
        $libraries[] = ['composer', 'require', 'behat/behat', '--working-dir', $this->projectPath, '--dev', '--no-suggest', '--no-scripts'];
        if ($configCollection->has(SymfonySkeletonConfig::class) || $configCollection->has(SymfonyWebsiteSkeletonConfig::class)) {
            $libraries[] = ['composer', 'require', 'friends-of-behat/symfony-extension:v2.1.0-BETA.1 as 2.0.9', 'friends-of-behat/mink', 'friends-of-behat/mink-extension', 'friends-of-behat/mink-browserkit-driver', '--working-dir', $this->projectPath, '--dev', '--no-suggest', '--no-scripts'];
        }

        return $libraries;
    }

    public function getMakefileContent(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/Behat/Makefile.twig');
    }

    public function getTestToRunOnBitbucketPipelines(ConfigCollection $configCollection): string
    {
        return $this->twig->render('Config/TestFramework/Behat/bitbucket-pipelines.yml.twig');
    }
}
