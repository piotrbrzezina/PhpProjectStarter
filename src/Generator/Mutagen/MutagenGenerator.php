<?php

declare(strict_types=1);

namespace App\Generator\Mutagen;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class MutagenGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(MutagenConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate mutagen.yml.dist'));

        $config = [];
        /** @var MutagenConfigInterface $configurator */
        foreach ($configCollection->get(MutagenConfigInterface::class) as $configurator) {
            $config[] = $configurator->getMutagenContent($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        file_put_contents($this->projectPath.'/mutagen.yml.dist', implode(PHP_EOL, array_filter($config)));

        $output->write($debugFormatter->stop(self::class, 'Generate mutagen.yml.dist finished', true));
    }
}
