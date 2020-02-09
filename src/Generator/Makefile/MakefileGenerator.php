<?php

declare(strict_types=1);

namespace App\Generator\Makefile;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class MakefileGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(MakefileConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate Makefile'));

        $config = [];
        /** @var MakefileConfigInterface $configurator */
        foreach ($configCollection->get(MakefileConfigInterface::class) as $configurator) {
            $config[] = $configurator->getMakefileContent($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }
        /** @var MakefileSetupProjectConfigInterface $configurator */
        foreach ($configCollection->get(MakefileSetupProjectConfigInterface::class) as $configurator) {
            $config[] = $configurator->getSetupProjectStep($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        file_put_contents($this->projectPath.'/Makefile', implode(PHP_EOL, array_filter($config)));

        $output->write($debugFormatter->stop(self::class, 'Generate Makefile finished', true));
    }
}
