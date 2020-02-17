<?php

declare(strict_types=1);

namespace App\Generator\Git;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class GitIgnoreGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(GitIgnoreConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate .gitignore'));

        $config = [];
        /** @var GitIgnoreConfigInterface $configurator */
        foreach ($configCollection->get(GitIgnoreConfigInterface::class) as $configurator) {
            $config[] = $configurator->getGitIgnoreContent($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        file_put_contents($this->projectPath.'/.gitignore', implode(PHP_EOL, array_filter($config)), FILE_APPEND);

        $output->write($debugFormatter->stop(self::class, 'Generate .gitignore finished', true));
    }
}
