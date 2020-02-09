<?php

declare(strict_types=1);

namespace App\Generator\CodeQuality;

use App\Config\ConfigCollection;
use App\Generator\GeneratorInterface;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Output\OutputInterface;

class EasyCodingStandardGenerator implements GeneratorInterface
{
    private string $projectPath;

    public function __construct(string $projectPath)
    {
        $this->projectPath = $projectPath;
    }

    public function generate(ConfigCollection $configCollection, OutputInterface $output): void
    {
        if (!$configCollection->has(EasyCodingStandardConfigInterface::class)) {
            return;
        }
        $debugFormatter = new DebugFormatterHelper();
        $output->write('', true);
        $output->write('', true);
        $output->write($debugFormatter->start(self::class, 'Generate ecs.yaml'));

        $config = [];
        /** @var EasyCodingStandardConfigInterface $configurator */
        foreach ($configCollection->get(EasyCodingStandardConfigInterface::class) as $configurator) {
            $config[] = $configurator->getEasyCodingStandardConfig($configCollection);
            $output->write($debugFormatter->progress(self::class, get_class($configCollection)));
        }

        file_put_contents($this->projectPath.'/ecs.yaml', implode(PHP_EOL, array_filter($config)));

        $output->write($debugFormatter->stop(self::class, 'Generate ecs.yaml finished', true));
    }
}
