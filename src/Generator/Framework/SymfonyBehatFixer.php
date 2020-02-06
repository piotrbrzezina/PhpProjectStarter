<?php

declare(strict_types=1);

namespace App\Generator\Framework;

use App\Config\ConfigCollection;
use App\Config\Framework\SymfonySkeletonConfig;
use App\Config\Framework\SymfonyWebsiteSkeletonConfig;
use App\Config\TestFramework\BehatConfig;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class SymfonyBehatFixer implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if ($configCollection->has(BehatConfig::class) &&
            (
                $configCollection->has(SymfonySkeletonConfig::class) ||
                $configCollection->has(SymfonyWebsiteSkeletonConfig::class)
            )
        ) {
            $debugFormatter = new DebugFormatterHelper();
            $output->write('', true);
            $output->write('', true);
            $output->write($debugFormatter->start(self::class, 'fix behat for symfony'));

            $bundles = explode("\n", (string) file_get_contents($this->projectPath.'/config/bundles.php'));
            array_splice($bundles, 3, 0, '    FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle::class => [\'test\' => true],');
            file_put_contents($this->projectPath.'/config/bundles.php', implode(PHP_EOL, $bundles));
            $output->write($debugFormatter->stop(self::class, 'fix behat for symfony finished', true));
        }
    }
}
